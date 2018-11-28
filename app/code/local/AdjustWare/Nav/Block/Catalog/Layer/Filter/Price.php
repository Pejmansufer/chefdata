<?php
/**
 * Layered Navigation Pro
 *
 * @category:    AdjustWare
 * @package:     AdjustWare_Nav
 * @version      2.5.11
 * @license:     HxObcXe62uQsQGs98TzFPLxwiM3aCakojlW7nJdmvI
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
class AdjustWare_Nav_Block_Catalog_Layer_Filter_Price extends Mage_Catalog_Block_Layer_Filter_Price
{
    private $_style;

    public function __construct()
    {
        parent::__construct();
        $this->_style = Mage::getStoreConfig('design/adjnav/price_style');
        $this->setTemplate('adjnav/filter/price_' . $this->_style . '.phtml');

        $this->_filterModelName = 'adjnav/catalog_layer_filter_price';
    }

    public function getVar(){
        return $this->_filter->getRequestVar();
    }

    public function getDelimeter() {
        if( version_compare( Mage::getVersion(),'1.7.0.0','>=' ) ) {
            return '-'; //magento 1.7+ "$from-$to"
        }
        return ',';//matento 1.6- "$index,$range"
    }

    public function getClearUrl()
    {
        $url = '';
        $query = Mage::helper('adjnav')->getParams();
//        if ('slider' != $this->_style && !empty($query[$this->getVar()])){
        if (!empty($query[$this->getVar()])){
            $query[$this->getVar()] = null;
            $url = Mage::getUrl('*/*/*', array(
                '_use_rewrite' => true,
                '_query'       => $query,
            ));
        }
        return $url;
    }

    public function isSelected($item)
    {
        return ($item->getValueString() == $this->_filter->getActiveState());
    }

    public function getItemUrl($_item)
    {
        $href = Mage::helper('adjnav')->trimBaseUrl();

        //if (!$hideLinks)
        {
            $href .= $this->getRequestPath();

            $params = Mage::helper('adjnav')->getParams();
            $params[$this->getVar()] = $_item->getValueString();

            if ($params = http_build_query($params))
            {
	            $href .= '?' . $params;
            }
        }
        return $href;
    }

    /**
     * Will return GET part of the request
     *
     *	@return string
     */
    public function getRequestPath()
    {
    	$request = Mage::app()->getRequest();

    	$requestPath = '';

    	if ($request->isXmlHttpRequest())
    	{
    		$requestPath = Mage::getSingleton('core/session')->getRequestPath();
    	}
    	else
    	{
    		Mage::getSingleton('core/session')->setRequestPath($requestPath = $request->getRequestString());
    	}

    	return $this->escapeHtml($requestPath);
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

    public function getCollectionSize()
    {
        return $this->getLayer()->getProductCollection()->getSize();
    }

    public function getPriceSliderData()
    {
        list($min,$max) = $this->_filter->getMinMaxPriceInt();

        list($from,$to) = explode($this->getDelimeter(), $this->_filter->getActiveState());

        $from = max($from, $min);

        if ($to) {
            $to = min($to, $max);
        }
        else {
            $to = $max;
        }

        if ($to<1 && $from<1) {
            $to = $max;
        }

        $range = $max - $min;
        $to = $this->_round($to, $range);
        $from = $this->_round($from, $range);

        $width = 170;

        if ($max) {
            if ($max == $min) {
                $firstOffset = 0;
                $secondOffset = $width;
            }
            else {
                $firstOffset  = ($from-$min)*$width/($max-$min);
                $secondOffset = ($to-$min)*$width/($max-$min);
            }
        }
        else {
            $firstOffset  = 0;
            $secondOffset = 0;
        }

        return $slideData[] = array(
            'to'             => $to,
            'from'           => $from,
            'width'          => $width,
            'firstOffset'    => $firstOffset,
            'secondOffset'   => $secondOffset,
            'min'            => $min,
            'max'            => $max,
        );
    }

    protected function _round($value, $range)
    {
        if($range <= 10)
        {
            $count = 2;
        }
        else if($range <= 100)
        {
            $count = 1;
        }
        else
        {
            $count = 0;
        }
        return round($value, $count);
    }

    public function getPriceInputData()
    {
        $items = $this->getItems();
        list($from, $to) = explode($this->getDelimeter(), $items[0]->getValueString());

        if ($from < 0.01) {
            $from = $this->__('From');
        }
        else {
            $from = round($from, 2);
        }

        if ($to < 0.01) {
            $to = $this->__('To');
        }
        else {
            $to = round($to, 2);
        }

        return $slideData[] = array(
            'to'    => $to,
            'from'  => $from,
        );
    }
}