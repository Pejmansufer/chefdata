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
 
class Magmodules_Reviewemail_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
		if(Mage::getSingleton('core/session')->getOrderId()) {
			$this->loadLayout();
			$this->renderLayout();
		} else {
			$this->_redirect('/');		
		}
    }

    public function checkAction() {
        
        $order_id = (int)Mage::app()->getRequest()->getParam('order_id');
		$store_id = Mage::app()->getStore()->getStoreId();      
        $email = Mage::app()->getRequest()->getParam('email');        
		$redirect = '';
		
		if($order_id && $email) {
		
			// LOAD ORDER
			$collection = Mage::getModel('sales/order')->getCollection()
						->addFieldToSelect(array('entity_id','increment_id'))
						->addAttributeToFilter('entity_id', array('eq' => $order_id))
						->addAttributeToFilter('customer_email', array('eq' => $email));
			$order = $collection->getFirstItem();

			// LOAD REVIEW EMAIL
			$reviewemail = Mage::getModel('reviewemail/reviewemail')->getCollection()
						->addFieldToSelect('*')					
						->addFieldToFilter('order_id', array('eq' => $order_id))
						->addFieldToFilter('customer_email', array('eq' => $email))
						->addFieldToFilter('email_id', 1);
			$reviewemail = $reviewemail->getFirstItem();
			$email_id = $reviewemail->getReviewemailId();
		
			// CHECK FOR REVIEWS	
			if(Mage::getStoreConfig('reviewemail/frontend/after_submit', $store_id)) {
				$reviews = Mage::getModel('reviewemail/reviews')->getCollection()->addFieldToFilter('order_id', array('eq' => $order_id));			
				$reviews = $reviews->getFirstItem();
				if($reviews->getId() > 0) {
					$redirect = 'success';
				}       	
			}
		
			if($redirect == 'success') {
				Mage::getSingleton('core/session')->setIncrementId($order->getIncrementId());
				Mage::getSingleton('core/session')->setOrderId($order_id);
				Mage::getSingleton('core/session')->setEmailId($email_id);
				Mage::getSingleton('core/session')->setEmail($email);			
				$this->_redirect('*/success/');        
			} else {
				if(($order_id) && ($email) && ($order->getEntityId())) {	
					Mage::getSingleton('core/session')->setIncrementId($order->getIncrementId());
					Mage::getSingleton('core/session')->setOrderId($order_id);
					Mage::getSingleton('core/session')->setEmailId($email_id);
					Mage::getSingleton('core/session')->setEmail($email);			
					$this->_redirect('*/form/');        
				} else {
					$session = Mage::getSingleton('core/session');
					$session->addError(Mage::helper('reviewemail')->__('Order not found!'));
					$this->_redirect('*/index/');
				}
			}
			
		} else {
			$session = Mage::getSingleton('core/session');
			$session->addError(Mage::helper('reviewemail')->__('Order not found!'));
			$this->_redirect('*/index/');		
		}
    }

    public function successAction() {
		if(Mage::getSingleton('core/session')->getOrderId()) {
			$this->loadLayout();
			$this->renderLayout();
		} else {
			$this->_redirect('/');		
		}
				
		// UNSET USED SESSION DATA
		Mage::getSingleton('core/session')->setIncrementId();
		Mage::getSingleton('core/session')->unsEmailId();
		Mage::getSingleton('core/session')->unsOrderId();
		Mage::getSingleton('core/session')->unsEmail();

    }

    public function unsubscribeAction() {
		$session = Mage::getSingleton('core/session');	
		$email = Mage::app()->getRequest()->getParam('email');    				

		// CHECK IF EMAIL EXISTS
		$reviewemail = Mage::getModel('reviewemail/reviewemail')->loadByEmail($email);
        
        if($reviewemail->getId()) {
			$exclude = Mage::getModel('reviewemail/exclude')->isOnList($email);
			if($exclude->getExcludeId()) {
				$session->addError(Mage::helper('reviewemail')->__('%s allready unsubscribed', $email));
			} else {
				$exclude_data = array("store_id" => $reviewemail->getStoreId(), "email" => $email, "date" => now(), "status" => 2);		
				$exclude = Mage::getModel('reviewemail/exclude')->setData($exclude_data);
				$exclude->save();	
				$session->addSuccess(Mage::helper('reviewemail')->__('%s successfully unsubscribed from service email messages', $email));
				
				$collection = Mage::getModel('reviewemail/reviewemail')->getCollection()
							->addFieldToFilter('status',array('eq'=>'scheduled'))
							->addFieldToFilter('customer_email',array('eq'=>$email))
							->load();  				

				if($collection) {
					foreach ($collection as $reviewemail) {
						$model = Mage::getModel('reviewemail/reviewemail');
						$reviewemail = Mage::getModel('reviewemail/reviewemail')->load($reviewemail->getReviewemailId());
						$reviewemail->setStatus('deleted')->setUpdatedAt(date('Y-m-d H:i:s'))->save();
					}	
				}					
			}
		} else {
			$session->addError(Mage::helper('reviewemail')->__('%s not found!', $email));		
		}	
		
		$this->_redirect('/');
    }
}