<?php

class SmashingMagazine_MyCarrier_Model_Carrier extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface {

    protected $_code = 'smashingmagazine_mycarrier';
    var $ManufacturerCount=0;
    var $FreightRate=0.0;
	var $UpsRate=0.0;
	var $FreightAvailable = false;
	var $UpsAvailable = false;

    public function collectRates(
        Mage_Shipping_Model_Rate_Request $request
    ) {
        $result = Mage::getModel('shipping/rate_result');

		$upsrate = $this->_getUpsRate($request);
		if($this->UpsAvailable && !$this->FreightAvailable)
		{
			$result->append($upsrate);
		}
		
		$freightQuote = $this->_getFreightRate($request);
        if($this->FreightAvailable)
		{
			$result->append($freightQuote);
			$result->append($this->_getLiftgateRate($request));
			//$result->append($this->_getResidentialRate($request));
			$result->append($this->_getLiftgateResidentialRate($request));	
		}
		
        return $result;
    }

	
	
	protected function _getUpsRate(Mage_Shipping_Model_Rate_Request $request)
    {		
		$total = 0;
        $price = 0;
		$items = array();
		$show = true;
		
        if ($request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
				
				$product_id = $item->getProductId();
				$productObj = Mage::getModel('catalog/product')->load($product_id);
				
                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }
				if($productObj->getWeight() == 0 || $productObj->getWeight() == null && $productObj->getAttributeText('freeshipping') != 'Free Shipping')
				{
					$show = false;
				}
				else if($productObj->getWeight() > 50 || $productObj->getData('shipping_method') == 'freight')
                {
                    $this->FreightAvailable = true;
                    continue;
                }
				else
					$this->UpsAvailable = true;

                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                            $product_id = $child->getProductId();
                            $productObj = Mage::getModel('catalog/product')->load($product_id);
                            $ship_price = $productObj->getData('freightclass'); //our shipping attribute code
                            $price += (float)$ship_price;
                        }
                    }
                } else {
                    
                    $freeshipping = $productObj->getAttributeText('freeshipping');
                    $freightclass = $productObj->getData('freightclass'); //our shipping attribute code
                    $weight = $productObj->getWeight();
                    $senderzip = $productObj->getData('senderzip');
                    $name = $productObj->getName();
                    $senderstate = $productObj->getData('senderstate');
                    $manufacturer = $productObj->getAttributeText('manufacturer');

                    $receiverzip = $request->getDestPostcode();
                    $receiverstate = $request->getDestRegionCode();

                    array_push($items, array(
						'name' => $name,
                        'freightclass' => $freightclass,
                        'weight' => $weight,
                        'senderzip' => $senderzip,
                        'senderstate' => $senderstate,
                        'receiverzip' => $receiverzip,
                        'receiverstate' => $receiverstate,
                        'manufacturer' => $manufacturer,
                        'freeshipping' => $freeshipping,
                        'qty' => $item->getQty()
                    ));

                }
            }
			$count = count($items);

			$keys = array_keys($items);

			
			for($i=0; $i<$count; $i++)
			{
				$key = $keys[$i];
				
				$ups = new upsRate($items[$key]['senderzip'],'','US');
				$upstemp = $ups->rate('',$items[$key]['receiverzip'],'','US',$items[$key]['weight'],'1');
				if(!$upstemp){
					$error_msg="$ups->error:$ups->errormsg"; 
					$show = false;
				}
				else
				{
					$total += $upstemp[0][total] * $items[$key]['qty'];
				}
			}
			
        }

		
		$result = Mage::getModel('shipping/rate_result');
		$rate = Mage::getModel('shipping/rate_result_method');
		$rate->setCarrier($this->_code);
		$rate->setCarrierTitle($this->getConfigData('title'));
		$rate->setMethod('customups');
		$rate->setMethodTitle('UPS Ground');
		
		if ($show && $this->UpsAvailable) {
			
			$rate->setPrice($total);
			$rate->setCost(0);
			$result->append($rate);

			$this->UpsRate = $total;
		}
		else
		{
			$error = Mage::getModel('shipping/rate_result_error');
			$error->setCarrier($this->_code);
			$error->setCarrierTitle($this->getConfigData('name'));
			$error->setErrorMessage($this->getConfigData('specificerrmsg'));
			$result->append($error);
		}
		return $result;
	
		
	}
	
    public function _getQuote(array $items)
    {
        $count = count($items);

        $wsLineItem = '';
        $keys = array_keys($items);

        for($i=0; $i<$count; $i++)
        {
            $key = $keys[$i];
            for($j=0; $j<$items[$key]['qty']; $j++)
            $wsLineItem  .= '<wwex:wsLineItem>
								<wwex:lineItemClass>'. $items[$key]['freightclass'] .'</wwex:lineItemClass>
								<wwex:lineItemWeight>'. round($items[$key]['weight']) .'</wwex:lineItemWeight>
								<wwex:lineItemDescription>Restaurant Equipment</wwex:lineItemDescription>
								<wwex:lineItemPieceType>PALLET</wwex:lineItemPieceType>
								<wwex:piecesOfLineItem>'. $items[$key]['qty'] .'</wwex:piecesOfLineItem>
								<wwex:lineItemHazmatInfo>
									<wwex:lineItemHazmatEmContactPhone>6152545449</wwex:lineItemHazmatEmContactPhone>
								</wwex:lineItemHazmatInfo>
							</wwex:wsLineItem>';

        }
        $first = $keys[0];

        $xml_builder = '
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wwex="http://www.wwexship.com">
           <soapenv:Header>
              <wwex:AuthenticationToken>
                 <wwex:loginId>bestintown1</wwex:loginId>
                 <wwex:password>708SHIP</wwex:password>
                 <wwex:licenseKey>eXgbrPKk8ovb7SQv</wwex:licenseKey>
                 <wwex:accountNumber>W319299184</wwex:accountNumber>
              </wwex:AuthenticationToken>
           </soapenv:Header>
           <soapenv:Body>
              <wwex:quoteSpeedFreightShipment>
                 <wwex:freightShipmentQuoteRequest>

                    <wwex:senderState>'.$items[$first]['senderstate'] .'</wwex:senderState>
                    <wwex:senderZip>'.  substr($items[$first]['senderzip'],0,5) .'</wwex:senderZip>

                    <wwex:receiverState>'. $items[$first]['receiverstate'] .'</wwex:receiverState>
                    <wwex:receiverZip>'. $items[$first]['receiverzip'] .'</wwex:receiverZip>
					<wwex:liftgatePickup>Y</wwex:liftgatePickup>
					

                    <wwex:commdityDetails>
                        <wwex:is11FeetShipment xsi:nil="true" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"/>
                        <wwex:handlingUnitDetails>
                        <wwex:wsHandlingUnit>
                            <wwex:typeOfHandlingUnit>PALLET</wwex:typeOfHandlingUnit>
                            <wwex:numberOfHandlingUnit>1</wwex:numberOfHandlingUnit>
                            <wwex:handlingUnitHeight xsi:nil="true" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"/>
                            <wwex:handlingUnitLength xsi:nil="true" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"/>
                            <wwex:handlingUnitWidth xsi:nil="true" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"/>
                            <wwex:lineItemDetails>
                                '.$wsLineItem.'
                            </wwex:lineItemDetails>
                        </wwex:wsHandlingUnit>
                       </wwex:handlingUnitDetails>
                    </wwex:commdityDetails>
                 </wwex:freightShipmentQuoteRequest>
              </wwex:quoteSpeedFreightShipment>
           </soapenv:Body>
        </soapenv:Envelope>';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://www.wwexship.com/webServices/services/SpeedFreightShipment");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml', 'SOAPAction: \" \"'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_builder);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ch_result = curl_exec($ch);
        curl_close($ch);

        $xml = simplexml_load_string($ch_result, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
        $ns = $xml->getNamespaces(true);
        $soap = $xml->children($ns['soapenv'])->children();

        $options = $soap->quoteSpeedFreightShipmentResponse->quoteSpeedFreightShipmentReturn->freightShipmentQuoteResults;

		$output = print_r($ch_result, true);
		file_put_contents('/var/www/html/error.txt', $output);
		
        return $options;

    }
	
    protected function _getFreightRate(Mage_Shipping_Model_Rate_Request $request)
    {
        $total = 0;
        $price = 0;
        $items = array();
		$count = 0;
		$show = true;
		
        if ($request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
				$count++;
				
				$product_id = $item->getProductId();
				$productObj = Mage::getModel('catalog/product')->load($product_id);
				
				if($productObj->getWeight() == 0 || $productObj->getWeight() == null && $productObj->getAttributeText('freeshipping') != 'Free Shipping')
				{
					$show = false;
				}
                else if ($item->getProduct()->isVirtual() || $item->getParentItem() || ($productObj->getWeight() <= 50 && $productObj->getData('shipping_method') != 'freight')) {
                    continue;
                }

                else if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                            $product_id = $child->getProductId();
                            $productObj = Mage::getModel('catalog/product')->load($product_id);
                            $ship_price = $productObj->getData('freightclass'); //our shipping attribute code
                            $price += (float)$ship_price;
                        }
                    }
                } 
				else {
                    
                    $freeshipping = $productObj->getAttributeText('freeshipping');
                    $freightclass = $productObj->getData('freightclass'); //our shipping attribute code
                    $weight = $productObj->getWeight();
                    $senderzip = $productObj->getData('senderzip');
                    $name = $productObj->getName();
                    $senderstate = $productObj->getData('senderstate');
                    $manufacturer = $productObj->getAttributeText('manufacturer');
					$pallet = $productObj->getData('pallet');
					
                    $receiverzip = $request->getDestPostcode();
                    $receiverstate = $request->getDestRegionCode();

                    array_push($items, array(
						'name' => $name,
                        'freightclass' => $freightclass,
                        'weight' => $weight,
                        'senderzip' => $senderzip,
                        'senderstate' => $senderstate,
                        'receiverzip' => $receiverzip,
                        'receiverstate' => $receiverstate,
                        'manufacturer' => $manufacturer,
                        'freeshipping' => $freeshipping,
                        'qty' => $item->getQty()
                    ));

                }
            }

			
			
            $arr = array();
            $k = 0;
            foreach ($items as $key => $item) {
                if ($item['freeshipping'] != 'Free Shipping')
                    $arr[$item['manufacturer']][++$k] = $item;
                $this->ManufacturerCount++;
            }

//array_keys($arr[array_keys($arr)[0]])[0]
            $quote = array();
			$upsquote = array();
			
            foreach ($arr as $item) {
                array_push($quote, $this->_getQuote($item));
            }
        }

		
		if($count == 0)
				$this->FreightAvailable = false;
			
        $show = true;
        foreach ($quote as $q) {
            if ($q->freightShipmentQuoteResult[2] != null)
                $total += $q->freightShipmentQuoteResult[2]->totalPrice;
			else if ($q->freightShipmentQuoteResult[1] != null)
                $total += $q->freightShipmentQuoteResult[1]->totalPrice;
			else if ($q->freightShipmentQuoteResult[0] != null)
                $total += $q->freightShipmentQuoteResult[0]->totalPrice * 1.2;
            else {
                $show = false;

            }
        }

        if ($show) {			
			$result = Mage::getModel('shipping/rate_result');
			$rate = Mage::getModel('shipping/rate_result_method');
			$rate->setCarrier($this->_code);
			$rate->setCarrierTitle($this->getConfigData('title'));
			$rate->setMethod('freight');
			$rate->setMethodTitle('Business Delivery');

			
            $rate->setPrice($total + $this->UpsRate);
            $rate->setCost(0);
            $result->append($rate);

            $this->FreightRate = $total;
        }
        else
        {
            $error = Mage::getModel('shipping/rate_result_error');
            $error->setCarrier($this->_code);
            $error->setCarrierTitle($this->getConfigData('name'));
            $error->setErrorMessage($this->getConfigData('specificerrmsg'));
            $result->append($error);
        }
        return $result;
    }

    protected function _getLiftgateRate($rate)
    {
        $result = Mage::getModel('shipping/rate_result');

        $rate = Mage::getModel('shipping/rate_result_method');
        /* @var $rate Mage_Shipping_Model_Rate_Result_Method */
        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod('liftgate');
        $rate->setMethodTitle('Business Delivery w/ Liftgate');
        $rate->setPrice($this->FreightRate + 50 * $this->ManufacturerCount);

        $rate->setCost(0);

        $result->append($rate);
        return $result;
    }
    protected function _getResidentialRate($rate)
    {
        $result = Mage::getModel('shipping/rate_result');

        $rate = Mage::getModel('shipping/rate_result_method');
        /* @var $rate Mage_Shipping_Model_Rate_Result_Method */
        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod('residential');
        $rate->setMethodTitle('Freight + ' . $this->ManufacturerCount . ' x Residential ($85)');
        $rate->setPrice($this->FreightRate + 110 * $this->ManufacturerCount);

        $rate->setCost(0);

        $result->append($rate);
        return $result;
    }
    protected function _getLiftgateResidentialRate($rate)
    {
        $result = Mage::getModel('shipping/rate_result');

        $rate = Mage::getModel('shipping/rate_result_method');
        /* @var $rate Mage_Shipping_Model_Rate_Result_Method */
        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod('liftresidential');
        $rate->setMethodTitle('Residential Delivery w/ Liftgate');
        $rate->setPrice($this->FreightRate + 110 * $this->ManufacturerCount);
        $rate->setCost(0);

        $result->append($rate);
        return $result;
    }

    public function getAllowedMethods() {
        return array(
            'customups' => 'UPS Ground',
			'freight' => 'Freight',
            'liftgate' => 'Liftgate',
            'liftresidential' => 'Liftgate and Residential'
        );
    }

	protected function getCountry($zip_postal){
		$ZIPREG=array(
			"US"=>"^\d{5}[\-]?(\d{4})?$",
			"UK"=>"^(GIR|[A-Z]\d[A-Z\d]??|[A-Z]{2}\d[A-Z\d]??)[ ]??(\d[A-Z]{2})$",
			"DE"=>"\b((?:0[1-46-9]\d{3})|(?:[1-357-9]\d{4})|(?:[4][0-24-9]\d{3})|(?:[6][013-9]\d{3}))\b",
			"CA"=>"^([ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ])\ {0,1}(\d[ABCEGHJKLMNPRSTVWXYZ]\d)$",
			"FR"=>"^(F-)?((2[A|B])|[0-9]{2})[0-9]{3}$",
			"IT"=>"^(V-|I-)?[0-9]{5}$",
			"AU"=>"^(0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2})$",
			"NL"=>"^[1-9][0-9]{3}\s?([a-zA-Z]{2})?$",
			"ES"=>"^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$",
			"DK"=>"^([D-d][K-k])?( |-)?[1-9]{1}[0-9]{3}$",
			"SE"=>"^(s-|S-){0,1}[0-9]{3}\s?[0-9]{2}$",
			"BE"=>"^[1-9]{1}[0-9]{3}$"
		);
		 
		if (preg_match("/".$ZIPREG["US"]."/i",$zip_postal)){
			
			return 'USA';
		} else if(preg_match("/".$ZIPREG["CA"]."/i",$zip_postal)){
			return 'CA';
		}
	}

}
class upsRate
{ 

   // You need to create userid ... at http://www.ec.ups.com    
 
    var $userid = "horizon-inter"; 
    var $passwd = "horizon133"; 
    var $accesskey = "0CF4ECAB8C6BD045"; 

    var $upstool='https://www.ups.com/ups.app/xml/Rate';

    var $request;  //'rate' for single service or 'shop' for all possible services

    var $service;
    var $pickuptype='01'; // 01 daily pickup
      /* Pickup Type
	01- Daily Pickup
	03- Customer Counter
	06- One Time Pickup
	07- On Call Air
	11- Suggested Retail Rates
	19- Letter Center
	20- Air Service Center
      */
    var $residential;

  //ship from location or shipper
    var $s_zip;
    var $s_state;
    var $s_country;  

  //ship to location
    var $t_zip;
    var $t_state;
    var $t_country;

  //package info
    var $package_type;  // 02 customer supplied package

    var $weight;


    var $error=0;
    var $errormsg;

    var $xmlarray = array(); 

    var $xmlreturndata = ""; 
          
   
    function upsRate($szip,$sstate,$scountry) 
    { 
        // init function 
      $this->s_zip = $szip;
      $this->s_state = $sstate;
      $this->s_country = $scountry;

    } 

    function construct_request_xml(){
      $xml="<?xml version=\"1.0\"?>
<AccessRequest xml:lang=\"en-US\">
   <AccessLicenseNumber>0CF4ECAB8C6BD045</AccessLicenseNumber>
   <UserId>horizon-inter</UserId>
   <Password>horizon133</Password>
</AccessRequest>
<?xml version=\"1.0\"?>
<RatingServiceSelectionRequest xml:lang=\"en-US\">
  <Request>
    <TransactionReference>
      <CustomerContext>Rating and Service</CustomerContext>
      <XpciVersion>1.0001</XpciVersion>
    </TransactionReference>
    <RequestAction>Rate</RequestAction> 
    <RequestOption>rate</RequestOption> 
  </Request>
  <PickupType>
  <Code>01</Code>
  </PickupType>
  <Shipment>
    <Shipper>
      <Address>
		<StateProvinceCode>$this->s_state</StateProvinceCode>
		<PostalCode>$this->s_zip</PostalCode>
		<CountryCode>US</CountryCode>
      </Address>
    </Shipper>
    <ShipTo>
      <Address>
		<StateProvinceCode>$this->t_state</StateProvinceCode>
		<PostalCode>$this->t_zip</PostalCode>
		<CountryCode>$this->t_country</CountryCode>	
		<ResidentialAddressIndicator>$this->residential</ResidentialAddressIndicator>
      </Address>
    </ShipTo>
    <Service>
    <Code>03</Code>
    </Service>
    <Package>
      <PackagingType>
        <Code>$this->package_type</Code>
        <Description>Package</Description>
      </PackagingType>
      <Description>Rate Shopping</Description>
      <PackageWeight>
        <Weight>$this->weight</Weight>
      </PackageWeight>
    </Package>
    <ShipmentServiceOptions/>
  </Shipment>
</RatingServiceSelectionRequest>";
       return $xml;
    }

 
    function rate($service='',$tzip,$tstate='',$tcountry='US',
	$weight,$residential=0,$packagetype='02') 
    { 
       if($service=='')
          $this->request = 'shop';
       else
	  $this->request = 'rate';

     	$this->service = $service; 
	$this->t_zip = $tzip;
	$this->t_state= $tstate;
	$this->t_country = $tcountry;
	$this->weight = $weight;
	$this->residential=$residential;
	$this->package_type=$packagetype;

	
        $this->__runCurl(); 
        $this->xmlarray = $this->_get_xml_array($this->xmlreturndata); 

        //check if error occurred
	if($this->xmlarray==""){
          $this->error=0;
	  $this->errormsg="Unable to retrieve the Shipping info";
	  return NULL;
        }
        $values = $this->xmlarray[RatingServiceSelectionResponse][Response][0];
 
	if($values[ResponseStatusCode] == 0){
	  $this->error=$values[Error][0][ErrorCode];
	  $this->errormsg=$values[Error][0][ErrorDescription];
          return NULL;
        }
	
 	return $this->get_rates();

    } 
    /** 
    * __runCurl() 
    * 
    * This is processes the curl command. 
    * 
    * @access    private 
    */ 
    function __runCurl() 
    { 

	$y = $this->construct_request_xml();
     	//echo $y;
/* 
	// -k : do not check certification for https
 	// -d with @ filename : read post data from the file 
	//curl -k -d @input http://www.neox.net/ups/curltest/t.php


	//generate tmp file name & save data
	$tmpfname = tempnam ("/tmp", "tmpfile");
	$fp = fopen($tmpfname,"w");
	fwrite($fp,$y);
	fclose($fp);

	$cmd = "curl -k -d @$tmpfname ".$this->upstool;
	$fp = popen($cmd,"r");
	while(($tmp=fread($fp,4096))!=null){
	  $output .=$tmp;
	}
	pclose($fp);
	
	unlink($tmpfname);

	$this->xmlreturndata = $output;

        //comment rest of the lines to the end of the function
*/

        $ch = curl_init(); 
        curl_setopt ($ch, CURLOPT_URL,"$this->upstool"); /// set the post-to url (do not include the ?query+string here!) 
        curl_setopt ($ch, CURLOPT_HEADER, 0); /// Header control 
        curl_setopt ($ch, CURLOPT_POST, 1);  /// tell it to make a POST, not a GET 
        curl_setopt ($ch, CURLOPT_POSTFIELDS, "$y");  /// put the querystring here starting with "?" 
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); /// This allows the output to be set into a variable $xyz 
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $this->xmlreturndata = curl_exec ($ch); /// execute the curl session and return the output to a variable $xyz 
        
        //echo "\n<P>:result--------------\n<P>";
	//echo $this->xmlreturndata;
        //curl_errno($ch);
        curl_close ($ch); /// close the curl session
 
    } 
    /** 
    * __get_xml_array($values, &$i) 
    * 
    * This is adds the contents of the return xml into the array for easier processing. 
    * 
    * @access    private 
    * @param    array    $values this is the xml data in an array 
    * @param    int    $i    this is the current location in the array 
    * @return    Array 
    */ 
    function __get_xml_array($values, &$i) 
    { 
      $child = array(); 
      if ($values[$i]['value']) array_push($child, $values[$i]['value']); 
     
      while (++$i < count($values)) 
      { 
        switch ($values[$i]['type']) 
        { 
          case 'cdata': 
            array_push($child, $values[$i]['value']); 
          break; 
     
          case 'complete': 
            $name = $values[$i]['tag']; 
            $child[$name]= $values[$i]['value']; 
            if($values[$i]['attributes']) 
            { 
              $child[$name] = $values[$i]['attributes']; 
            } 
          break; 
     
          case 'open': 
            $name = $values[$i]['tag']; 
            $size = sizeof($child[$name]); 
            if($values[$i]['attributes']) 
            { 
                 $child[$name][$size] = $values[$i]['attributes']; 
                  $child[$name][$size] = $this->__get_xml_array($values, $i); 
            } 
            else 
            { 
                  $child[$name][$size] = $this->__get_xml_array($values, $i); 
            } 
          break; 
     
          case 'close': 
            return $child; 
          break; 
        } 
      } 
      return $child; 
    } 
     
    /** 
    * _get_xml_array($data) 
    * 
    * This is adds the contents of the return xml into the array for easier processing. 
    * 
    * @access    private 
    * @param    string    $data this is the string of the xml data 
    * @return    Array 
    */ 
    function _get_xml_array($data) 
    { 
      $values = array(); 
      $index = array(); 
      $array = array(); 
      $parser = xml_parser_create(); 
      xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); 
      xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0); 
      xml_parse_into_struct($parser, $data, $values, $index); 
      xml_parser_free($parser); 
     
      $i = 0; 
     
      $name = $values[$i]['tag']; 
      $array[$name] = $values[$i]['attributes']; 
      $array[$name] = $this->__get_xml_array($values, $i); 
     
      return $array; 
    } 
     
    /** 
    * get_general_package_info() 
    * 
    * This function parses the big array and returns the general info about the shiped package. 
    * 
    * @access    public 
    * @return    Array 
    */ 
    function get_rates() 
    { 
     // $retArray = array('service'=>'','basic'=>0,'option'=>0,'total'=>0,'days'=>'','time'=>'');
          
        $retArray=array();
 
        $values = $this->xmlarray[RatingServiceSelectionResponse][RatedShipment];
      	$ct = count($values);
        for($i=0;$i<$ct;$i++){
	  $current=&$values[$i];

          $retArray[$i]['service'] = $current[Service][0][Code]; 
          $retArray[$i]['basic'] = $current[TransportationCharges][0][MonetaryValue];
	  $retArray[$i]['option'] = $current[ServiceOptionsCharges][0][MonetaryValue];
	  $retArray[$i]['total'] = $current[TotalCharges][0][MonetaryValue];
	  $retArray[$i]['days'] =  $current[GuaranteedDaysToDelivery];
	  $retArray[$i]['time'] = $current[ScheduledDeliveryTime];
        }

        unset($values); 
         
        return $retArray; 
    } 

}