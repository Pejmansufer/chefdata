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
 
class Magmodules_Reviewemail_Adminhtml_ReviewcouponsController extends Mage_Adminhtml_Controller_Action {

	protected function _initAction() {
		$this->loadLayout()->_setActiveMenu('reviewemail/items')->_addBreadcrumb(Mage::helper('reviewemail')->__('Items Manager'), Mage::helper('reviewemail')->__('Item Manager'));		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()->renderLayout();
	}

 	public function massDeleteAction() {
        $couponIds = $this->getRequest()->getParam('coupons');        
        if(!is_array($couponIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewemail')->__('Please select item(s)'));
        } else {
            try {
                foreach ($couponIds as $coupon_id) {
                    $coupons = Mage::getModel('reviewemail/coupons')->load($coupon_id);
                    $coupon_real_id = $coupons->getCouponId();
                    $coupons->delete();
                    
					if(version_compare(Mage::getVersion(), '1.7', '>=')){
                    	$coupon = Mage::getModel('salesrule/coupon')->load($coupon_real_id);
	                    $coupon->delete();                    
					} else {
                    	$coupon = Mage::getModel('salesrule/coupon')->load($coupon_real_id);
						$rule_id = $coupon->getRuleId();
                    	$couponrule = Mage::getModel('salesrule/rule')->load($rule_id);
	                    $couponrule->delete();                    					
					}  
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewemail')->__('Total of %d coupons(s) were successfully deleted', count($couponIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

 	public function massActivateAction() {
        $couponIds = $this->getRequest()->getParam('coupons');        
        if(!is_array($couponIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewemail')->__('Please select item(s)'));
        } else {
            try {
                foreach ($couponIds as $coupon_id) {
                    $coupons = Mage::getModel('reviewemail/coupons')->load($coupon_id);
                    $coupon_real_id = $coupons->getCouponId();
                    $coupon = Mage::getModel('salesrule/coupon')->load($coupon_real_id);
                    $coupon->setTimesUsed(0)->save();             
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewemail')->__('Total of %d coupons(s) were successfully (re)activated', count($couponIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

 	public function massExtendAction() {
        $couponIds = $this->getRequest()->getParam('coupons');        
        if(!is_array($couponIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewemail')->__('Please select item(s)'));
        } else {
            try {
                foreach ($couponIds as $coupon_id) {
                    $coupons = Mage::getModel('reviewemail/coupons')->load($coupon_id);
                    $coupon_real_id = $coupons->getCouponId();
                    $coupon = Mage::getModel('salesrule/coupon')->load($coupon_real_id);
					$expiration_date = date('Y-m-d H:i:s', time() + ($expire_days * 86400));
					$coupon->setExpirationDate($expiration_date)->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewemail')->__('Total of %d coupons(s) were successfully extended', count($couponIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }   

	protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('newsletter/reviewemail/coupons');
    }     	    	
}