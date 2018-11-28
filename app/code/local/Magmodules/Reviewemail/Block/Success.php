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
 
class Magmodules_Reviewemail_Block_Success extends Mage_Core_Block_Template {

	public function _prepareLayout()  {
		$this->getLayout()->getBlock('head')->setTitle(Mage::helper('reviewemail')->__('Reviewemail Form'));
		$this->setTemplate('reviewemail/success.phtml');
		$this->setReviewemailSuccessMsg(Mage::getStoreConfig('reviewemail/frontend/success'));

		if(Mage::getSingleton('customer/session')->getReviewemailCoupon()){
			$msg = Mage::getStoreConfig('reviewemail/coupon/coupon_message');
			$coupon = Mage::getSingleton('customer/session')->getReviewemailCoupon();
			$msg = str_replace('{{coupon_code}}', $coupon['code'], $msg);
			$msg = str_replace('{{expiration_date}}', Mage::helper('core')->formatDate($coupon['expiration_date'], 'medium', true), $msg);
			$this->setReviewemailCoupon($msg);
		}	
		return parent::_prepareLayout();
	}
	
}