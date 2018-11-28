<?php
class Extendware_EWLayeredNav_Block_Catalog_Layer_Filter_Price extends Mage_Catalog_Block_Layer_Filter_Price
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewlayerednav/catalog/layer/filter/price/' . $this->getPriceFilterType() . '.phtml');
        $this->_filterModelName = 'ewlayerednav/catalog_layer_filter_price';
    }
    
    public function getPriceFilterType() {
    	return Mage::helper('ewlayerednav/config')->getPriceFilterType();
    }

    public function getRequestVar() {
    	return $this->_filter->getRequestVar();
    }
    
    public function getClearUrl()
    {
        $url = '';
        if ('slider' != $this->getPriceFilterType()){
            $url = Mage::getUrl('*/*/*', array(
                '_current'     => true, 
                '_use_rewrite' => true, 
                '_query'       => array($this->getRequestVar() => null),
            )); 
        }
        return $url;
    }
    
    public function hasSelectedOptions() {
    	return $this->_filter->hasInputtedRange();
    }
    
    public function isSelected($item)
    {
        return ($item->getValueString() == $this->_filter->getActiveState());        
    }
    
    public function getSymbol()
    {
        $s = $this->getData('symbol');
        if (!$s){
            $code = Mage::app()->getStore()->getCurrentCurrencyCode();
            $s = trim(Mage::app()->getLocale()->currency($code)->getSymbol());
            
            $this->setData('symbol', $s);
        }
        return $s;
    }
}