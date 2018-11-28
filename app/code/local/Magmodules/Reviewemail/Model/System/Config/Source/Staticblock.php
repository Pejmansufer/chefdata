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
 
class Magmodules_Reviewemail_Model_System_Config_Source_Staticblock {

    protected $_options;
    
    public function toOptionArray() {
        if(!$this->_options) {
            $this->_options = array(array('value' => 0, 'label' => Mage::helper('catalog') -> __('--Please select--')));
            $options = Mage::getResourceModel('cms/block_collection')->load()->toOptionArray();
            $this->_options = array_merge($this->_options, $options);
        }
        return $this->_options;
    }

} 