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
 
class Magmodules_Reviewemail_Model_System_Config_Model_Enable extends Mage_Core_Model_Config_Data {

    protected function _beforeSave() {
        Mage::register('reviewemail_modify_event', true, true);
        parent::_beforeSave();
    }

    public function has_value_for_configuration_changed($observer) {
        if (Mage::registry('reviewemail_modify_event') == true) {
            Mage::unregister('reviewemail_modify_event');
            Magmodules_Reviewemail_Model_System_Config_Model_License::isEnabled();
        }
    }
    
}
