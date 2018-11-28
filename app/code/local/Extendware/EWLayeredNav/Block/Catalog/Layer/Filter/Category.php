<?php
class Extendware_EWLayeredNav_Block_Catalog_Layer_Filter_Category extends Mage_Catalog_Block_Layer_Filter_Category
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewlayerednav/catalog/layer/filter/category.phtml');
        $this->_filterModelName = 'ewlayerednav/catalog_layer_filter_category'; 
    }
    
    public function getRequestVar(){
        return $this->_filter->getRequestVar();
    }
    
    public function getClearUrl()
    {
    	return $this->getUrl('*/*/*', array('_current' => true, '_query' => array($this->_filter->getRequestVar() => null)));
    }
    
	public function hasSelectedOptions() {
    	$filter = (int) Mage::app()->getRequest()->getParam($this->_filter->getRequestVar());
    	return (empty($filter) === false);
    }

}