<?php

class Trustpilot_Reviews_Helper_OrderData extends Mage_Core_Helper_Abstract
{
    public function tryGetEmail($order)
    {
        if ($this->isEmpty($order))
            return '';

        if (!($this->isEmpty($order->getCustomerEmail())))
            return $order->getCustomerEmail();

        else if (!($this->isEmpty($order->getShippingAddress()->getEmail())))
            return $order->getShippingAddress()->getEmail();

        else if (!($this->isEmpty($order->getBillingAddress()->getEmail())))
            return $order->getBillingAddress()->getEmail();

        else if (!($this->isEmpty($order->getCustomerId())))
            return Mage::getModel('customer/customer')->load($order->getCustomerId())->getEmail();

        else if (Mage::getSingleton('customer/session')->isLoggedIn())
            return Mage::getSingleton('customer/session')->getCustomer()->getEmail();
        
        return '';
    }
    
    public function isEmpty($var)
    { 
        return empty($var);
    }

    public function getSkus($products)
    {
        $skus = array();
        foreach ($products as $product) {
            array_push($skus, $product['sku']);
        }

        return $skus;
    }
    
    public function getProducts($order)
    {
        $products = array();
        try {
            $items = $order->getAllVisibleItems();
            foreach ($items as $i) {
                $product = Mage::getModel('catalog/product')->load($i->getProductId());
                $brand = $product->getAttributeText('manufacturer');
                array_push(
                    $products,
                    array(
                        'productUrl' => $product->getProductUrl(),
                        'name' => $product->getName(),
                        'brand' => $brand ? $brand : '',
                        'sku' => $product->getSku(),
                        'imageUrl' => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) 
                            . 'catalog/product' . $product->getImage()
                    )
                );
            }
        } catch (Exception $e) {
            // Just skipping products data if we are not able to collect it
        }

        return $products;
    }
}
