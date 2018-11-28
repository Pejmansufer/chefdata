<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */
class Trmmarketing_Promotionalpopup_Model_Opacity
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'0.1', 'label'=>Mage::helper('promotionalpopup')->__('10%')),
            array('value'=>'0.2', 'label'=>Mage::helper('promotionalpopup')->__('20%')),
            array('value'=>'0.3', 'label'=>Mage::helper('promotionalpopup')->__('30%')),
			array('value'=>'0.4', 'label'=>Mage::helper('promotionalpopup')->__('40%')),
			array('value'=>'0.5', 'label'=>Mage::helper('promotionalpopup')->__('50%')),
			array('value'=>'0.6', 'label'=>Mage::helper('promotionalpopup')->__('60%')),
			array('value'=>'0.7', 'label'=>Mage::helper('promotionalpopup')->__('70%')),
			array('value'=>'0.8', 'label'=>Mage::helper('promotionalpopup')->__('80%')),
			array('value'=>'0.9', 'label'=>Mage::helper('promotionalpopup')->__('90%')),
			array('value'=>'1', 'label'=>Mage::helper('promotionalpopup')->__('100%')),                       
        );
    }

}

