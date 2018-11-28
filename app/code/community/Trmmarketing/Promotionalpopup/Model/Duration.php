<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */
class Trmmarketing_Promotionalpopup_Model_Duration
{
    public function toOptionArray()
    {
        return array(
			
            array('value'=>'.25', 'label'=>Mage::helper('promotionalpopup')->__('1/4 Second')),
            array('value'=>'.5', 'label'=>Mage::helper('promotionalpopup')->__('1/2 Second')),
            array('value'=>'.75', 'label'=>Mage::helper('promotionalpopup')->__('3/4 Second')),
			array('value'=>'1', 'label'=>Mage::helper('promotionalpopup')->__('1 Second')),
			array('value'=>'1.25', 'label'=>Mage::helper('promotionalpopup')->__('1 1/4 Seconds')),
			array('value'=>'1.5', 'label'=>Mage::helper('promotionalpopup')->__('1 1/2 Seconds')),
			array('value'=>'1.75', 'label'=>Mage::helper('promotionalpopup')->__('1 3/4 Seconds')),
			array('value'=>'2', 'label'=>Mage::helper('promotionalpopup')->__('2 Seconds')),                       
        );
    }

}

