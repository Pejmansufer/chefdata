<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */
class Trmmarketing_PopupWidgets_Model_Promotionids
{
    public function toOptionArray()
    {
        
				$rules = Mage::getResourceModel('salesrule/rule_collection')->load();
				
				$returnArray = array();
				$returnArray[] = array('value'=>'0', 'label'=>Mage::helper('promotionalpopup')->__('Do not generate coupon'));
				foreach ($rules as $rule) :
					if(Mage::getVersion() >= 1.7):
						if (($rule->getIsActive())&&($rule['use_auto_generation'] == 1)) : 
							$returnArray[] = array('value'=>$rule->getId(), 'label'=>$rule->getName());
						endif;
					else:
						if ($rule->getIsActive()) : 
							$returnArray[] = array('value'=>$rule->getId(), 'label'=>$rule->getName());
						endif;
					endif;
                endforeach;
				
				
					
                return $returnArray;
		
		
		
    }

}