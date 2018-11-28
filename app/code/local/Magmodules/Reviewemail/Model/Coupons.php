<?php
/**
 * Magmodules.eu - http://www.magmodules.eu - info@magmodules.eu
 * =============================================================
 * NOTICE OF LICENSE [Single domain license]
 * This source file is subject to the EULA that is
 * available through the world-wide-web at:
 * http://www.magmodules.eu/license-agreement/
 * =============================================================
 * @category    Magmodules
 * @package     Magmodules_Reviewemail
 * @author      Magmodules <info@magmodules.eu>
 * @copyright   Copyright (c) 2015 (http://www.magmodules.eu)
 * @license     http://www.magmodules.eu/license-agreement/  
 * =============================================================
 */
 
class Magmodules_Reviewemail_Model_Coupons extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('reviewemail/coupons');
    }

    public function getCouponCode($reviewemail_id, $order_id = '', $shopreview_id = '') {

		if($reviewemail_id > 0) {
			$reviewemail = Mage::getModel('reviewemail/reviewemail')->load($reviewemail_id);		
			$store_id = $reviewemail->getStoreId();
		} else {
			$model  = Mage::getModel('reviewemail/reviewemail');
			$order = Mage::getModel("sales/order")->load($order_id);
			$store_id = $order->getStoreId(); 
		}		
				
        if(!Mage::getStoreConfig('reviewemail/coupon/enable', $store_id))
            return '';
		if(!Mage::getStoreConfig('reviewemail/coupon/ruleid', $store_id))
			return 'Rule not found';

		if($reviewemail_id) {
			$coupons = Mage::getModel('reviewemail/coupons')->load($reviewemail_id, 'reviewemail_id');
		} else {
			$coupons = Mage::getModel('reviewemail/coupons')->load($order_id, 'order_id');		
		}	
		
		if(!$coupons->getCouponId()) {			
			if(version_compare(Mage::getVersion(), '1.7', '>=')){
				$coupon = $this->generateCouponNewMethod($reviewemail_id, $order_id, $shopreview_id);			
			} else {
				$coupon = $this->generateCouponOldMethod($reviewemail_id, $order_id, $shopreview_id);						
			}
			return $coupon; 
   		}
    }  
	
	public function generateCouponNewMethod($reviewemail_id, $order_id = '', $shopreview_id = '') {
		
		if($reviewemail_id > 0) {
			$reviewemail = Mage::getModel('reviewemail/reviewemail')->load($reviewemail_id);		
			$customer_name = $reviewemail->getCustomerName();
			$customer_email	= $reviewemail->getCustomerEmail();
			$store_id = $reviewemail->getStoreId();
			$type = 'Type';
			$increment_id = $reviewemail->getIncrementId();
			$order_id = $reviewemail->getOrderId();
		} else {
			$model  = Mage::getModel('reviewemail/reviewemail');
			$order = Mage::getModel("sales/order")->load($order_id);
			$increment_id = $order->getIncrementId(); 
			$store_id = $order->getStoreId(); 
			$customer_email = Mage::getStoreConfig('reviewemail/testing/test_email', $store_id);
			$customer_name = $order->getCustomerName();
			$type = 'Type';
			$reviewemail_id = '0';				
		}
		
		$model = Mage::getModel('salesrule/rule');
		$model->load(Mage::getStoreConfig('reviewemail/coupon/ruleid', $store_id));
			
		$massGenerator = $model->getCouponMassGenerator();
		$massGenerator->setData(array(
			'rule_id' 			=> Mage::getStoreConfig('reviewemail/coupon/ruleid', $store_id),
			'length' 			=> Mage::getStoreConfig('reviewemail/coupon/length', $store_id),
			'format' 			=> Mage::getStoreConfig('reviewemail/coupon/format', $store_id),
			'prefix' 			=> Mage::getStoreConfig('reviewemail/coupon/prefix', $store_id),
			'suffix' 			=> Mage::getStoreConfig('reviewemail/coupon/suffix', $store_id),
			'dash' 				=> Mage::getStoreConfig('reviewemail/coupon/dash', $store_id),
			'qty' 				=> 1,
			'uses_per_coupon' 	=> 1,
			'uses_per_customer' => 1
		));

		$massGenerator->generatePool();
		$generated = $massGenerator->getGeneratedCount();

		$latest = max($model->getCoupons());
		$coupon = $latest->getData();

		// WORKAROUND FOR COUPON EXPIRATION
		if($expire_days = Mage::getStoreConfig('reviewemail/coupon/expire', $store_id)) {        
			$oCoupon = Mage::getModel('salesrule/coupon')->load($coupon['code'], 'code');
			$expiration_date = date('Y-m-d H:i:s', time() + ($expire_days * 86400));
			$oCoupon->setExpirationDate($expiration_date)->save();
			$coupon['expiration_date'] = $expiration_date;
		} else {
			$expiration_date = '';
		}	

		if($generated != 1) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewemail')->__('There was a problem with coupon generation.'));
		}
		
		$coupons = Mage::getModel('reviewemail/coupons');			
		$coupons->setCouponId($coupon['coupon_id'])
				->setCustomerName($customer_name)
				->setCustomerEmail($customer_email)
				->setReviewemailId($reviewemail_id)
				->setShopreviewId($shopreview_id)
				->setOrderId($order_id)				
				->setIncrementId($increment_id)
				->setStoreId($store_id)		
				->save();				
		
		if($reviewemail_id > 0) {
			$reviewemail->setCoupon($coupon['coupon_id'])->save();
		}	
		
		return $coupon;	
	}

	public function generateCouponOldMethod($reviewemail_id, $order_id = '', $shopreview_id = '') {

		if($reviewemail_id > 0) {
			$reviewemail = Mage::getModel('reviewemail/reviewemail')->load($reviewemail_id);		
			$customer_name = $reviewemail->getCustomerName();
			$customer_email	= $reviewemail->getCustomerEmail();
			$store_id = $reviewemail->getStoreId();
			$increment_id = $reviewemail->getIncrementId();
			$order_id = $reviewemail->getOrderId();
			$type = 'Type';
		} else {
			$model = Mage::getModel('reviewemail/reviewemail');
			$order = Mage::getModel("sales/order")->load($order_id);
			$increment_id = $order->getIncrementId(); 
			$store_id = $order->getStoreId(); 
			$customer_email = Mage::getStoreConfig('reviewemail/testing/test_email', $store_id);
			$customer_name = $order->getCustomerName();
			$type = 'Type';
			$reviewemail_id = '0';				
		}
		
		$rule = Mage::getModel('salesrule/rule')->load(Mage::getStoreConfig('reviewemail/coupon/ruleid', $store_id));

		$coupon_code = strtoupper(Mage::helper('core')->getRandomString(Mage::getStoreConfig('reviewemail/coupon/length', $store_id))); 
		$coupon_code = Mage::getStoreConfig('reviewemail/coupon/prefix', $store_id) . $coupon_code . Mage::getStoreConfig('reviewemail/coupon/suffix', $store_id);		
		$coupon_name = 'Reviewemail: ' . $customer_name . ' (ID: #' . $increment_id . ') - ' . $rule->getName(); 

		if($expire_days = Mage::getStoreConfig('reviewemail/coupon/expire', $store_id)) {        
			$expiration_date = date('Y-m-d H:i:s', time() + ($expire_days * 86400));
		} else {
			$expiration_date = '';		
		}
	 	
	 	$coupon = Mage::getModel('salesrule/rule');
        $coupon->setName($coupon_name)
			  ->setCouponCode($coupon_code)
			  ->setDescription($coupon_name)
			  ->setFromDate($rule->getFromDate())
			  ->setToDate($expiration_date)			  
			  ->setUsesPerCoupon($rule->getUsesPerCoupon())
			  ->setUsesPerCustomer($rule->getUsesPerCustomer())
			  ->setCustomerGroupIds($rule->getCustomerGroupIds())
			  ->setIsActive(1)
			  ->setConditionsSerialized($rule->getConditionsSerialized())
			  ->setActionsSerialized($rule->getConditionsSerialized())
			  ->setStopRulesProcessing($rule->getConditionsSerialized())
			  ->setIsAdvanced(1)
			  ->setProductIds($rule->getProductIds())
			  ->setSortOrder($rule->getSortOrder())
			  ->setSimpleAction($rule->getSimpleAction())
			  ->setCouponType(2)
			  ->setDiscountAmount($rule->getDiscountAmount())
			  ->setDiscountStep($rule->getDiscountStep())
			  ->setSimpleFreeShipping($rule->getSimpleFreeShipping())
			  ->setTimesUsed(0)
			  ->setIsRss($rule->getIsRss())
			  ->setWebsiteIds($rule->getWebsiteIds())
			  ->save();	

		$oCoupon = Mage::getModel('salesrule/coupon')->load($coupon_code, 'code');						
		
		$coupons = Mage::getModel('reviewemail/coupons');			
		$coupons->setCouponId($oCoupon['coupon_id'])
				->setCustomerName($customer_name)
				->setCustomerEmail($customer_email)
				->setReviewemailId($reviewemail_id)
				->setShopreviewId($shopreview_id)
				->setOrderId($order_id)				
				->setIncrementId($increment_id)								
				->setStoreId($store_id)				
				->save();	
			
		if($reviewemail_id > 0) {		
			$reviewemail->setCoupon($coupon_code)->save();
		}
				  		
		return $oCoupon;
	}
	
	public function sendCouponEmail($reviewemail_id, $coupon, $order_id = '') {

		if(($reviewemail_id || $order_id) && $coupon) {	

			// LOAD SHOPREVIEW & SENDER
			if($reviewemail_id) {
				$reviewemail = Mage::getModel('reviewemail/reviewemail')->load($reviewemail_id);
				$coupon = Mage::getModel('reviewemail/coupons')->load($reviewemail_id, 'reviewemail_id');
				$store_id = $coupon->getStoreId();
			} else {
				$coupon = Mage::getModel('reviewemail/coupons')->load($order_id, 'order_id');
				$store_id = $coupon->getStoreId();
			}
				
			$coupon_msg = '';
			$coupon_code = '';
			$coupon_experation_date = '';
						
			if(($coupon->getId() > 0)) {			
				$couponr = Mage::getModel('salesrule/coupon')->load($coupon->getCouponId());
				$coupon_msg = Mage::getStoreConfig('reviewemail/coupon/coupon_message', $store_id);
				$coupon_msg = str_replace('{{coupon_code}}', $couponr->getCode(), $coupon_msg);
				$coupon_experation_date = Mage::helper('core')->formatDate($couponr->getExpirationDate(), 'medium', true);
				$coupon_msg = str_replace('{{expiration_date}}', $coupon_experation_date, $coupon_msg);
				$coupon_code = $couponr->getCode();
				
				$sender =  Mage::getStoreConfig('reviewemail/config/reviewemail_sender', $store_id);
				$template = Mage::getStoreConfig('reviewemail/coupon/email_template', $store_id);
			
				$customerEmail = $coupon->getCustomerEmail();
				$customerName =  $coupon->getCustomerName();

				$bcc_email = Mage::getStoreConfig('reviewemail/config/reviewemail_bccemail', $store_id); 
				$bcc_enabled = Mage::getStoreConfig('reviewemail/config/reviewemail_bccenabled', $store_id); 		

				$vars = array('nickname' => $customerName, 'coupon_msg' => $coupon_msg, 'coupon_code' => $coupon_code, 'coupon_experation_date' => $coupon_experation_date);		
			
				// SEND EMAIL
				Mage::getSingleton('core/translate')->setTranslateInline(false);

				if($bcc_email && $bcc_enabled) {	
					$send = Mage::getModel('core/email_template')->addBcc($bcc_email)->sendTransactional($template, $sender, $customerEmail, $customerName, $vars, $store_id);	
				} else {
					$send = Mage::getModel('core/email_template')->sendTransactional($template, $sender, $customerEmail, $customerName, $vars, $store_id);		
				}

				Mage::getSingleton('core/translate')->setTranslateInline(true);	
			
				return $send;
			}
		}
		
		return false;	
	}	

    public function checkForCouponCode($reviewemail_id) {
		$coupons = Mage::getModel('reviewemail/coupons')->load($reviewemail_id, 'reviewemail_id');
		if($coupons) {
			return $coupons;
		}
		return false;	
	}	
	
}