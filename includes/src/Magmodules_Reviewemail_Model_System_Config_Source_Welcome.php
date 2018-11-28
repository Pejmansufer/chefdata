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
 
class Magmodules_Reviewemail_Model_System_Config_Source_Welcome {

	public function toOptionArray() {
		$shopreviewlink = array();
		$shopreviewlink[] = array('value'=>'0', 'label'=> Mage::helper('reviewemail')->__('No'));				
		$shopreviewlink[] = array('value'=>'1', 'label'=> Mage::helper('reviewemail')->__('Yes, static block'));				
		$shopreviewlink[] = array('value'=>'2', 'label'=> Mage::helper('reviewemail')->__('Yes, content'));
		return $shopreviewlink;
	}
	
}