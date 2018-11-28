<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */
class Trmmarketing_Promotionalpopup_Model_Tracking
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'None', 'label'=>Mage::helper('promotionalpopup')->__('Do not send tracking data')),
            array('value'=>'GAClassic', 'label'=>Mage::helper('promotionalpopup')->__('Google Analytics Classic')),
            array('value'=>'GAUniversal', 'label'=>Mage::helper('promotionalpopup')->__('Google Analytics Universal')),
			array('value'=>'GTM', 'label'=>Mage::helper('promotionalpopup')->__('Google Tag Manager (Requires DataLayer)')),            
        );
    }

}

