<?php
abstract class Equity_FacebookPixel_Block_Abstract extends Mage_Core_Block_Template
{

    abstract protected function _canShow();

    protected function _toHtml() {
        if ( !$this->_canShow() ) {
            return '';
        }

        return parent::_toHtml();
    }

    protected function _getLastAddedProduct()
    {
        $productID = Mage::getSingleton('checkout/session')->getLastAddedProductId(true);
        return Mage::getModel('catalog/product')->load($productID);
    }

    public function getStoreCurrency() {
        return $this->_getHelper()->getStoreCurrency();
    }

    public function getConversionValue() {
        if ( !$this->_getHelper()->getCartAmount() ){
            return 0.01;
        }
        return number_format($this->_getHelper()->getCartAmount(),2);
    }

    protected function _getConfigHelper() {
        return Mage::helper('equity_facebookpixel/config');
    }

    protected function _getHelper() {
        return Mage::helper('equity_facebookpixel');
    }

    protected function _getCheckout() {
        return Mage::getSingleton('checkout/session');
    }

    protected function _getProductTrackID($product)
    {
        if( $this->_getConfigHelper()->getProductTrackType() == "id" ){
            return "'" . $this->_getProductID($product) . "'";
        }

        return "'" . $this->_getProductSKU($product) . "'";
    }

    protected function _getProductName($product)
    {
        return $product->getName();
    }

    protected function _getCategoryPath()
    {
        $names = array();
        $category = $this->_getCategory();

        if( is_null($category) ) return '';

        $path = $category->getPath();
        $path = explode("/",$path);

        unset($path[0],$path[1]);

        foreach($path as $id){
            $category = Mage::getModel('catalog/category')->setStoreId( Mage::app()->getStore()->getId() )->load($id);
            $names[] = $category->getName();
        }

        $return = implode(" > ", $names);

        return $return;
    }

    protected function _trackDetails()
    {
        return $this->_getConfigHelper()->getDynamicProducts();
    }

    protected function _getProductPrice($product)
    {
        return number_format( $product->getFinalPrice(),2,'.','' );
    }

    protected function _getProductID($product)
    {
        return $product->getId();
    }

    protected function _getProductSKU($product)
    {
        return $product->getSKU();
    }

    protected function _getCategory()
    {
        return Mage::registry('current_category');
    }

}
