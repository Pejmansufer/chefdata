<?php
/*
* Copyright (c) 2015 www.magebuzz.com
*/
class Magebuzz_Flatrateperproduct_Model_Carrier_Flatrateperproduct
	extends Mage_Shipping_Model_Carrier_Abstract
	implements Mage_Shipping_Model_Carrier_Interface {		 
	protected $_code = 'flatrateperproduct';
	public function collectRates(Mage_Shipping_Model_Rate_Request $request) {			
		if (!$this->getConfigFlag('active')) {
			return false;
		}
		$shippingPrice =0;
		if ($request->getAllItems()) {		
			foreach ($request->getAllItems() as $item) {
				$product = Mage::getModel('catalog/product')->load($item->getProductId());
				if($product->getTypeId()=='configurable' || $product->getTypeId()=='bundle') {
					continue;
				}
				$shipCost= $product->getShipCost();							
				if($shipCost==null || $shipCost==0){ 
					$shippingPrice += $item->getQty() * $this->getConfigData('price');			 		 
				}
				else { 
					$shippingPrice += $item->getQty() * $shipCost;	
				}
			}
		}   
		
		if ($shippingPrice == 0 && Mage::helper('flatrateperproduct')->isHideIfZero()) {
			return false;
		}
		$result = Mage::getModel('shipping/rate_result');			
		if ($shippingPrice !== false) {
			$method = Mage::getModel('shipping/rate_result_method');
			$method->setCarrier('flatrateperproduct');
			$method->setCarrierTitle($this->getConfigData('title'));
			$method->setMethod('flatrateperproduct');
			$method->setMethodTitle($this->getConfigData('name'));
			
			if($request->getFreeShipping()){
				$shippingPrice = '0.00';
			}
			
			$method->setPrice($shippingPrice);
			$method->setCost($shippingPrice);
			$result->append($method);
		}
		return $result;
  }
	
	public function getAllowedMethods() {
		return array('flatrateperproduct'=>$this->getConfigData('name'));
	}
}