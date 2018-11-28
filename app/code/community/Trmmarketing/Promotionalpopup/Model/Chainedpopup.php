<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */
class Trmmarketing_Promotionalpopup_Model_Chainedpopup
{
    public function toOptionArray()
    {
        
				$collection = Mage::getModel('promotionalpopup/promotionalpopup')->getCollection();
				$returnArray = array();
				$returnArray[] = array('value'=>'', 'label'=>Mage::helper('promotionalpopup')->__('None'));
				foreach ($collection as $item) : 
				$returnArray[] = array('value'=>$item->getPromotionalpopupId(), 'label'=>$item->getTitle());
				
                endforeach;
				
				
					
                return $returnArray;
		
		
    }

}