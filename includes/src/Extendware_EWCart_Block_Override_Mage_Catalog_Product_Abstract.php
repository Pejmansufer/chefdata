<?php
class Extendware_EWCart_Block_Override_Mage_Catalog_Product_Abstract extends Extendware_EWCart_Block_Override_Mage_Catalog_Product_Abstract_Bridge
{
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

        	if (Mage::helper('ewcart/config')->isCartOptionsEnabled() === true) {
            	return $this->helper('checkout/cart')->getAddUrl($product, $additional);
            }
        }
       	
		return parent::getAddToCartUrl($product, $additional);
    }
}
