<?php
class Extendware_EWLayeredNav_Block_Catalogsearch_Layer_Filter_Price extends Extendware_EWLayeredNav_Block_Catalog_Layer_Filter_Price
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewlayerednav/catalog/layer/filter/price/' . $this->getPriceFilterType() . '.phtml');
        $this->_filterModelName = 'ewlayerednav/catalogsearch_layer_filter_price';
    }
}