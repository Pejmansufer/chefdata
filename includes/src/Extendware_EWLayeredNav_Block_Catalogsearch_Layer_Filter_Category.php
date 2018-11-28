<?php
class Extendware_EWLayeredNav_Block_Catalogsearch_Layer_Filter_Category extends Extendware_EWLayeredNav_Block_Catalog_Layer_Filter_Category
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewlayerednav/catalog/layer/filter/category/search.phtml');
        $this->_filterModelName = 'ewlayerednav/catalogsearch_layer_filter_category'; 
    }
}
