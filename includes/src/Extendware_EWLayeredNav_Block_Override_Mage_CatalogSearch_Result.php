<?php
class Extendware_EWLayeredNav_Block_Override_Mage_CatalogSearch_Result extends Extendware_EWLayeredNav_Block_Override_Mage_CatalogSearch_Result_Bridge
{
	public function isEnabled() {
    	return Mage::helper('ewlayerednav/config')->isSearchEnabled();
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