<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */

class Trmmarketing_Promotionalpopup_Block_Promotionalpopup extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getPopup()     
     { 
        if (!$this->hasData('promotionalpopup')) {
            $this->setData('promotionalpopup', Mage::registry('promotionalpopup'));
        }
        return $this->getData('promotionalpopup');
        
    }
}