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
 
class Magmodules_Reviewemail_Model_Observer {

    public function processScheduleAfterShipment($observer) {
		$shipment = $observer->getEvent()->getShipment();
		$order = $shipment->getOrder();
		if (Mage::helper('reviewemail/data')->getModuleEnabled($order->getStoreId())) {
			$this->sheduleReminder($order);
		}
    }

    public function processSchedule($observer) {
        $order = $observer->getEvent()->getOrder();
		if (Mage::helper('reviewemail/data')->getModuleEnabled($order->getStoreId())) {
			$this->sheduleReminder($order);
		}
    }
 
    public function sheduleReminder($order) {
        $reviewemail = Mage::getModel('reviewemail/reviewemail');
        $store = Mage::app()->getStore($order->getStoreId());
        $name = $order->getBillingAddress()->getName();

		// Check if Email is on Exclude List
        $exclude = Mage::getModel('reviewemail/exclude')->isOnList($order->getCustomerEmail());
		if($exclude->getId() > 0)
            return;
	
		// Check if Order is allready in list
		$onlist = $reviewemail->loadByOrderId($order->getId(), '1');
		if($onlist->getId())
			return;
				
		// Check if order status == selected config status
		$configstatus = Mage::getStoreConfig('reviewemail/first_email/reviewemail_status', $order->getStoreId());
		$orderstatus = $order->getStatus();
		if($orderstatus != $configstatus)
			return;	

		// Check if date of order > maximum date offset
		$backlog = Mage::getStoreConfig('reviewemail/first_email/reviewemail_backlog', $order->getStoreId());	
		if($backlog > 0) {
			$max_str = (strtotime(now()) - ($backlog * 86400));
			$order_str = strtotime($order->getCreatedAt()); 			
			if($order_str < $max_str) 
				return;
		}	
		                		
		// Set time offset
 		$offset = ((int)Mage::getStoreConfig('reviewemail/first_email/reviewemail_days', $order->getStoreId()) * 86400);
         
        $reviewemail->setCustomerName($name)
					->setEmailId(1)
					->setStoreId($order->getStoreId())
					->setCustomerId($order->getCustomerId())
					->setOrderId($order->getId())
					->setCreatedAt(date('Y-m-d H:i:s'))
					->setSheduledAt(date('Y-m-d H:i:s', time() + $offset))
					->setCustomerEmail($order->getCustomerEmail())
					->setIncrement($order->getIncrementId())       
					->save();
    }
      
    public function sendByCron() {  
		$cron = Mage::getStoreConfig('reviewemail/config/cron');
		$enabled = Mage::getStoreConfig('reviewemail/general/enabled');	
	
        if($enabled && $cron) {	
			$collection = Mage::getModel('reviewemail/reviewemail')->getCollection()
						->addFieldToFilter('status',array('eq'=>'scheduled'))
						->addFieldToFilter('sheduled_at',array('lt'=> now()))
						->setPageSize(10)
						->setCurPage(1)
						->load();        
	
			foreach ($collection as $reviewemail) {
				$model = Mage::getModel('reviewemail/reviewemail');
				$sent = $model->sendReviewemail($reviewemail->getReviewemailId(), '', $reviewemail->getEmailId());	
				$reviewemail = Mage::getModel('reviewemail/reviewemail')->load($reviewemail->getReviewemailId());
				$reviewemail->setStatus('sent')->setSentAt(date('Y-m-d H:i:s'))->setUpdatedAt(date('Y-m-d H:i:s'))->save();
				if(Mage::getStoreConfig('reviewemail/second_email/second_email_enabled')) {
					$secondemail = Mage::getModel('reviewemail/reviewemail')->loadByOrderId($reviewemail->getOrderId(), '2'); 
					if(!$secondemail->getReviewemailId()) {		
						$offset = ((int)Mage::getStoreConfig('reviewemail/second_email/reviewemail_days', $reviewemail->getStoreId()) * 86400);
						$save = Mage::getModel('reviewemail/reviewemail');						
						$save->setCustomerName($reviewemail->getName())
							->setEmailId(2)
							->setStoreId($reviewemail->getStoreId())
							->setCustomerName($reviewemail->getCustomerName())
							->setCustomerId($reviewemail->getCustomerId())
							->setOrderId($reviewemail->getOrderId())
							->setCreatedAt(date('Y-m-d H:i:s'))
							->setSheduledAt(date('Y-m-d H:i:s', time() + $offset))
							->setCustomerEmail($reviewemail->getCustomerEmail())
							->setIncrement($reviewemail->getIncrement())       
							->save();										
					}
				}
			} 
			 
		}
    }    
	
	public function setAdminBlock(Varien_Event_Observer $observer) {			
        if(Mage::getStoreConfig('reviewemail/config/orders_block')) {
			$block = $observer->getBlock();
			$class = get_class($block);			
			if(($class == 'Mage_Adminhtml_Block_Sales_Order_View_Messages')) {			
				$transport = $observer->getEvent()->getTransport();	
				if(empty($transport)) {
					return $this;
				}
				$output = $observer->getTransport()->getHtml();
				$reviewsblock = $block->getLayout()->createBlock('reviewemail/reviewemail')->setTemplate('magmodules/reviewemail/sales/order/view/reviewemail.phtml')->toHtml();
				$observer->getTransport()->setHtml($reviewsblock . $output);						
			}
		}
	}	
   
    public function checkCoupons($observer) {
        $coupon_enable = Mage::getStoreConfig('reviewemail/coupon/enable');
        $coupon_trigger = Mage::getStoreConfig('reviewemail/coupon/trigger');
        if($coupon_enable && $coupon_trigger && ($coupon_trigger != 'onpage')) {
			$status_id = $observer->object->getStatusId();
			if(($status_id == 1) && ($coupon_trigger == 'onapprove')) {
				$review_id = $observer->object->getReviewId();
				$reviews = Mage::getModel('reviewemail/reviews')->getCollection()->addFieldToFilter('review_id', $review_id);
				$reviews = $reviews->getFirstItem();
				$reviewemail_id = $reviews->getReviewemailId();
				$order_id = $reviews->getOrderId();
				
				if($reviewemail_id > 0) {				
					$coupons = Mage::getModel('reviewemail/coupons')->load($reviewemail_id, 'reviewemail_id');
				} else {
					$coupons = Mage::getModel('reviewemail/coupons')->load($order_id, 'order_id');				
				}
					
				if(!$coupons->getId()) {
					$coupon = Mage::getModel('reviewemail/coupons')->getCouponCode($reviewemail_id, $order_id);				
					Mage::getModel('reviewemail/coupons')->sendCouponEmail($reviewemail_id, $coupon, $order_id);		
				}				
							
			}		
    	}
    }
    
}