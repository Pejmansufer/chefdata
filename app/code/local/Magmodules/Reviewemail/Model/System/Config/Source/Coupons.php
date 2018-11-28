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
 
class Magmodules_Reviewemail_Model_System_Config_Source_Coupons {

	public function toOptionArray() {
		$rules = Mage::getResourceModel('salesrule/rule_collection')->load();
		$list = array('' => Mage::helper('reviewemail')->__('Please choose rule'));
		if($rules) {
			foreach($rules as $rule) {
				if($rule->getCouponType()==2) {
					$list[$rule->getId()] = $rule->getName();
				}	
			}
		}
		return $list;
	}

}