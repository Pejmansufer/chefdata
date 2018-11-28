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
 
class Magmodules_Reviewemail_Model_System_Config_Source_Coupontrigger {

	public function toOptionArray() {
		$trigger = array();
		$trigger[] = array('value'=>'onpage', 'label'=> Mage::helper('reviewemail')->__('After placing review (on page)'));				
		$trigger[] = array('value'=>'onapprove', 'label'=> Mage::helper('reviewemail')->__('After approved review (by email)'));				
		return $trigger;
	}
	
}