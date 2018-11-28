<?php
class Equity_FacebookPixel_Model_Observer {

    public function trackCustomerRegister()
    {
        Mage::getSingleton('core/session')->setIsRegistered('1');
    }

    public function productAddedToCart()
    {
        Mage::getSingleton('core/session')->setIsAddedToCart('1');
    }

    public function afterContactSubmitObserver()
    {
        Mage::getSingleton('core/session')->setIsContactSubmit('1');
    }

}
