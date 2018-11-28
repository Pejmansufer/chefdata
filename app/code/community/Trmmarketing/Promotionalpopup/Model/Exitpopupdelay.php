<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */
class Trmmarketing_Promotionalpopup_Model_Exitpopupdelay
{
    public function toOptionArray()
    {
        return array(
			
            array('value'=>'1000', 'label'=>Mage::helper('promotionalpopup')->__('1 Second')),
            array('value'=>'2000', 'label'=>Mage::helper('promotionalpopup')->__('2 Seconds')),
            array('value'=>'3000', 'label'=>Mage::helper('promotionalpopup')->__('3 Seconds')),
			array('value'=>'4000', 'label'=>Mage::helper('promotionalpopup')->__('4 Seconds')),
			array('value'=>'5000', 'label'=>Mage::helper('promotionalpopup')->__('5 Seconds')),
			array('value'=>'10000', 'label'=>Mage::helper('promotionalpopup')->__('10 Seconds')),
			array('value'=>'20000', 'label'=>Mage::helper('promotionalpopup')->__('20 Seconds')),
			array('value'=>'30000', 'label'=>Mage::helper('promotionalpopup')->__('30 Seconds')),
			array('value'=>'40000', 'label'=>Mage::helper('promotionalpopup')->__('40 Seconds')),
			array('value'=>'50000', 'label'=>Mage::helper('promotionalpopup')->__('50 Seconds')),
			array('value'=>'60000', 'label'=>Mage::helper('promotionalpopup')->__('1 Minute')),
			array('value'=>'120000', 'label'=>Mage::helper('promotionalpopup')->__('2 Minutes')),
			array('value'=>'180000', 'label'=>Mage::helper('promotionalpopup')->__('3 Minutes')),
			array('value'=>'240000', 'label'=>Mage::helper('promotionalpopup')->__('4 Minutes')),
			array('value'=>'300000', 'label'=>Mage::helper('promotionalpopup')->__('5 Minutes')),                       
        );
    }

}

