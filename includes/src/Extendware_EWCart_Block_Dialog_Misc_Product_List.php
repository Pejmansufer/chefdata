<?php
class Extendware_EWCart_Block_Dialog_Misc_Product_List extends Extendware_EWCart_Block_Dialog_Abstract
{
	public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewcart/dialog/misc/product/list.phtml');
    }
    
	protected function _toHtml()
    {
    	if ($this->mHelper('config')->getListMode() == 'crosssell') {
		    $block = $this->getLayout()->createBlock('catalog/product_list_crosssell');
			if ($block) {
				$block->setParentBlock($this->getParentBlock());
				$block->setTemplate($this->getTemplate());
				return $block->toHtml();
			}
    	} elseif ($this->mHelper('config')->getListMode() == 'upsell') {
		    $block = $this->getLayout()->createBlock('catalog/product_list_upsell');
			if ($block) {
				$block->setParentBlock($this->getParentBlock());
				$block->setTemplate($this->getTemplate());
				return $block->toHtml();
			}
    	} elseif ($this->mHelper('config')->getListMode() == 'related') {
		    $block = $this->getLayout()->createBlock('catalog/product_list_related');
			if ($block) {
				$block->setParentBlock($this->getParentBlock());
				$block->setTemplate($this->getTemplate());
				return $block->toHtml();
			}
    	} elseif ($this->mHelper('config')->getListMode() == 'alsoviewed') {
    		if (Mage::getSingleton('ewcore/module')->load('Extendware_EWAlsoViewed')->isActive() === true) {
			    $block = $this->getLayout()->createBlock('ewalsoviewed/catalog_product_list_viewed');
				if ($block) {
					$block->setParentBlock($this->getParentBlock());
					$block->setTemplate($this->getTemplate());
					return $block->toHtml();
				}
    		}
    	} elseif ($this->mHelper('config')->getListMode() == 'alsobought') {
    		if (Mage::getSingleton('ewcore/module')->load('Extendware_EWAlsoBought')->isActive() === true) {
			    $block = $this->getLayout()->createBlock('ewalsobought/catalog_product_list_bought');
				if ($block) {
					$block->setParentBlock($this->getParentBlock());
					$block->setTemplate($this->getTemplate());
					return $block->toHtml();
				}
    		}
    	}
    	
    	return '';
    }
}