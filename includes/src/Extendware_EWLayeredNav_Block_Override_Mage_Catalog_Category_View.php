<?php
class Extendware_EWLayeredNav_Block_Override_Mage_Catalog_Category_View extends Extendware_EWLayeredNav_Block_Override_Mage_Catalog_Category_View_Bridge
{
	public function isEnabled() {
    	return Mage::helper('ewlayerednav/config')->isBrowseEnabled();
    }
    
    public function getProductListHtml()
    {
    	if (!$this->isEnabled()) {
    		return parent::getProductListHtml();
    	}
    	
        $html = parent::getProductListHtml();
        $html = Mage::helper('ewlayerednav')->processProductListHtml($html);
        return $html;
    }   
}
