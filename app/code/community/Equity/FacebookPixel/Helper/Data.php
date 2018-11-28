<?php
class Equity_FacebookPixel_Helper_Data extends Mage_Core_Helper_Abstract{

    public function getLastOrder() {
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        if ($orderId) {
            $order = Mage::getModel('sales/order')->load($orderId);
            return $order;
        }

        return null;
    }

    public function getCartAmount() {
        $cartAmount = Mage::getSingleton('checkout/cart')->getQuote()->getGrandTotal();
        if($cartAmount) return $cartAmount;
        return null;
    }

    public function getStoreCurrency($store = null) {
        return Mage::app()->getStore($store)->getCurrentCurrencyCode();
    }

}
