<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */
class Trmmarketing_Promotionalpopup_Model_Observer
{
    public function subscriptionConfirmation(Varien_Event_Observer $observer)
    {
		
	$event = $observer->getEvent();
    $subscriber = $event->getDataObject();
    $data = $subscriber->getData();

    $statusChange = $subscriber->getIsStatusChanged();

    // Trigger if user is now subscribed and there has been a status change:
    if ($data['subscriber_status'] == "1" && $statusChange == true) {
      // Insert your code here
	  
	  $collection = Mage::getModel('trmmarketing_subscribeconfirmed/popupemail')->getCollection()
							->addFieldToFilter('email', array('eq' => $subscriber->getEmail() ) ); 
							
							// delete all previous emails
							foreach ($collection as $item) :
								
		$email = $item['email'];
		$template = $item['template'];
		$ruleid = $item['ruleid'];
		$codelength = $item['codelength'];
	  
	  
	  
	  ////////////////
	  $identity = Mage::getStoreConfig('trmpopupconfig/trmpopupsubscribe_group/identity',Mage::app()->getStore());
			
			$identityName = Mage::getStoreConfig('trans_email/ident_'.$identity.'/name');
			$identityEmail = Mage::getStoreConfig('trans_email/ident_'.$identity.'/email');
			
			if($template == "") $template = Mage::getStoreConfig('trmpopupconfig/trmpopupsubscribe_group/template',Mage::app()->getStore());
		
			
			// Define the sender
			$sender = Array('name' => $identityName,
							'email' => $identityEmail);
			
			// Set the store
			$store = Mage::app()->getStore();
			
			
			//// BOF Generate Coupon
			// checks if there is a coupon assigned, if not it will skip generation
			if($ruleid != "0"):
				if($codelength == "0") $codelength = 14; 
				
				$promotionCode = $this->generateCoupon($ruleid, $codelength);
			
			endif;
			//// EOF Generate Coupon
			
			// In this array, you set the variables you use in your template
			$vars = Array('subscriber_email' => $email,
						  'unsubscribe_link' => $unsubscribeLink,
						  'coupon_code' => $promotionCode);
			
			// Translation        
			$translate  = Mage::getSingleton('core/translate');
			
			// Send email
			try {
			$transactionalEmail = Mage::getModel('core/email_template');
			$transactionalEmail->sendTransactional($template, $sender, $email, $email, $vars, $store->getId());
			
			} catch (Exception $e) { 
				//Mage::log('Newsletter send exception: ' . $e , null, 'promotionalpopups.log');
			}
		
	
			// Translate email        
			$translate->setTranslateInline(true);
			
			/*end of sending emails */	
	  ///////////////
	  
	  ///////////////
	  
			  function sendPopupEmail($email){
					
					$identity = Mage::getStoreConfig('trmpopupconfig/trmpopupsubscribe_group/identity',Mage::app()->getStore());
					
					$identityName = Mage::getStoreConfig('trans_email/ident_'.$identity.'/name');
					$identityEmail = Mage::getStoreConfig('trans_email/ident_'.$identity.'/email');
					
					$emailTemplate = Mage::getStoreConfig('trmpopupconfig/trmpopupsubscribe_group/template',Mage::app()->getStore());
				
					
					$sender = Array('name' => $identityName,
									'email' => $identityEmail);
					
					
					$store = Mage::app()->getStore();
					
					$vars = Array('subscriber_email' => $email);
					
					// Translation        
					$translate  = Mage::getSingleton('core/translate');
					
					// Send email
					try {
					$transactionalEmail = Mage::getModel('core/email_template');
					$transactionalEmail->sendTransactional($emailTemplate,
																			 $sender,
																			 $email,
																			 $vars,
																			 $store->getId());
					
					} catch (Exception $e) { 
					
					}
				
			
																	 
					
					// Translate        
					$translate->setTranslateInline(true);
					
					
					/*end of sending emails */	
						
					}
			
			
			
				Mage::log('Popup email delete', null, 'popupemail_queue.log');
				$item->delete();
			endforeach;

	  
	  
	  
	  /////////////
	  
	  
    }
    return $observer;
		
    }
	
	
	
	
	public function generateCoupon($ruleid, $codelength){
		
		if(Mage::getVersion() >= 1.7):
			
			$generator = Mage::getModel('salesrule/coupon_massgenerator');
			
			$data = array(
				'max_probability'   => .25,
				'max_attempts'      => 10,
				'uses_per_customer' => 1,
				'uses_per_coupon'   => 1,
				'qty'               => 1, //number of coupons to generate
				'length'            => $codelength, //length of coupon string
				
				'format'          => Mage_SalesRule_Helper_Coupon::COUPON_FORMAT_ALPHANUMERIC,
				'rule_id'         => $ruleid //the id of the rule you will use as a template
			);
			
			$generator->validateData($data);
			
			$generator->setData($data);
			
			$generator->generatePool();
			
			
			$salesRule = Mage::getModel('salesrule/rule')->load($data['rule_id']);
			$collection = Mage::getResourceModel('salesrule/coupon_collection')
						->addRuleToFilter($salesRule)
						->addGeneratedCouponsFilter()
						->getLastItem();
			
			
			return $collection->getCode();
			
		else:
		
			
			
	
			$couponCode = $this->generateLegacyCouponCode($codelength);
			
			$checkCoupon = Mage::getModel('salesrule/coupon')->load($couponCode, 'code');
			$checkRule = Mage::getModel('salesrule/rule')->load($checkCoupon->getRuleId());
			
			
				do { 
					$couponCode = $this->generateLegacyCouponCode($codelength);
				} while ($checkRule['coupon_code'] == $couponCode); 
					
			
			
			$coupon = Mage::getModel('salesrule/rule')->load($ruleid);
			
			$coupon->setId(NULL);
			$coupon->setName($coupon->getName() . ' - ' . $couponCode);
			$coupon->setCouponCode($couponCode);
			
			$coupon->save();
					
			return $couponCode;	
		
		endif;
			
		
	}
	
	public function generateLegacyCouponCode($length = null)
    {
        $rndId = crypt(uniqid(rand(),1));
        $rndId = strip_tags(stripslashes($rndId));
        $rndId = str_replace(array(".", "$"),"",$rndId);
        $rndId = strrev(str_replace("/","",$rndId));
            if (!is_null($rndId)){
            return strtoupper(substr($rndId, 0, $length));
            }
        return strtoupper($rndId);
    }

}

