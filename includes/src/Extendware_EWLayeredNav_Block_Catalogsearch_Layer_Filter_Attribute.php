<?php
class Extendware_EWLayeredNav_Block_Catalogsearch_Layer_Filter_Attribute extends Extendware_EWLayeredNav_Block_Catalog_Layer_Filter_Attribute
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewlayerednav/catalog/layer/filter/attribute.phtml');
        $this->_filterModelName = 'ewlayerednav/catalogsearch_layer_filter_attribute';
    }
    
	public function getClearUrl($route = null)
    {
    	$route = 'catalogsearch/result/index';
        return parent::getClearUrl($route);
    }
}