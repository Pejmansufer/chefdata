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
 
class Magmodules_Reviewemail_Adminhtml_ReviewexcludeController extends Mage_Adminhtml_Controller_Action {

	protected function _initAction() {
		$this->loadLayout()->_setActiveMenu('reviewemail/items')->_addBreadcrumb(Mage::helper('reviewemail')->__('Items Manager'), Mage::helper('reviewemail')->__('Item Manager'));
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()->renderLayout();
	}
	
  	public function addAction() {
		$orderId = $this->getRequest()->getParam('order_id');
		$_order = Mage::getModel('sales/order')->load($orderId);
		$email = $_order->getCustomerEmail();
		
		try {
			$exclude_data = array("store_id" => $_order->getStoreId(), "email" => $email, "date" => now(), "status" => 1);		
			$exclude = Mage::getModel('reviewemail/exclude')->setData($exclude_data)->save();
			$reviewemail = Mage::getModel('reviewemail/reviewemail')->load($email, 'customer_email');
			
			if($reviewemail->getStatus() == 'scheduled') {
				$reviewemail->setStatus('deleted')->setUpdatedAt(date('Y-m-d H:i:s'))->save();
			}
			
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewemail')->__('%s added to the exclude list', $email));
		} catch (Exception $e) {
			$session->addError(Mage::helper('reviewemail')->__('Unable to add %s to exclude list', $email));
		} 
			
		$this->_redirect("adminhtml/sales_order/view", array('order_id'=> $orderId));
	}    	


  	public function removeAction() {		
		$orderId = $this->getRequest()->getParam('order_id');
		$_order = Mage::getModel('sales/order')->load($orderId);
		$email = $_order->getCustomerEmail();
		$excludelist = Mage::getModel('reviewemail/exclude')->isOnList($email); 
				
		if($excludelist->getExcludeId() > 0) {
			try {
				Mage::getModel('reviewemail/exclude')->loadExclude($excludelist->getExcludeId())->delete();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewemail')->__('%s removed from exclude list', $email));
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewemail')->__('Unable to remove %s from exclude list', $email));
			} 			
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewemail')->__('Unable to remove %s from exclude list', $email));
		}
		
		$this->_redirect("adminhtml/sales_order/view", array('order_id'=>$orderId));
	} 

	protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('newsletter/reviewemail/exclude');
    }
	
}