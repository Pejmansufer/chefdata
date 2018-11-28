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
 
class Magmodules_Reviewemail_Model_System_Config_Source_Reviewlink {

	public function toOptionArray() {
		$reviewlink = array();
		$reviewlink[] = array('value'=>'0', 'label'=> Mage::helper('reviewemail')->__('No'));				
		$reviewlink[] = array('value'=>'1', 'label'=> Mage::helper('reviewemail')->__('Yes, Button: Review Email Page'));		
		$reviewlink[] = array('value'=>'2', 'label'=> Mage::helper('reviewemail')->__('Yes, Button: The Product Review Page'));
		$reviewlink[] = array('value'=>'3', 'label'=> Mage::helper('reviewemail')->__('Yes, Button: The Product Page'));
		$reviewlink[] = array('value'=>'4', 'label'=> Mage::helper('reviewemail')->__('Yes, Button: A Custom Link'));		
		$reviewlink[] = array('value'=>'5', 'label'=> Mage::helper('reviewemail')->__('Yes, Only Products'));				
		return $reviewlink;
	}
	
}