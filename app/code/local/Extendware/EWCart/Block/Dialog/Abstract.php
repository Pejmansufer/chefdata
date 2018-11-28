<?php
abstract class Extendware_EWCart_Block_Dialog_Abstract extends Extendware_EWCore_Block_Mage_Core_Template
{
 	protected $javascript = array();
 	
 	public function getProduct() {
 		return Mage::registry('ew:product');
 	}
 	
	public function getJavascript() {
 		return $this->javascript;
 	}
 	
 	public function addJavascript($data) {
 		$this->javascript[] = $data;
 		return $this;
 	}
 	
 	public function getIsInCart() {
 		return (bool)(Mage::app()->getRequest()->getParam('in_cart') or Mage::app()->getRequest()->getParam('__source') == 'cart');
 	}
 	
	public function getAddToCartUrl($product, $additional = array())
    {
    	if ($product->getTypeInstance(true)->hasRequiredOptions($product)) {
            if (!isset($additional['_escape'])) {
                $additional['_escape'] = true;
            }
            if (!isset($additional['_query'])) {
                $additional['_query'] = array();
            }
            $additional['_query']['options'] = 'cart';

            return $this->getProductUrl($product, $additional);
        }
        return $this->helper('checkout/cart')->getAddUrl($product, $additional);
    }
    
	public function getProductUrl($product, $additional = array())
    {
        if ($this->hasProductUrl($product)) {
            if (!isset($additional['_escape'])) {
                $additional['_escape'] = true;
            }
            return $product->getUrlModel()->getUrl($product, $additional);
        }

        return '#';
    }
    
	public function hasProductUrl($product)
    {
        if ($product->getVisibleInSiteVisibilities()) {
            return true;
        }
        if ($product->hasUrlDataObject()) {
            if (in_array($product->hasUrlDataObject()->getVisibility(), $product->getVisibleInSiteVisibilities())) {
                return true;
            }
        }

        return false;
    }
}