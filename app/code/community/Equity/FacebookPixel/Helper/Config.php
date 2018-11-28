<?php

class Equity_FacebookPixel_Helper_Config extends Mage_Core_Helper_Abstract {

    const 	ID = 'equity_facebookpixel_section/facebook_pixel/',
        OPTIONS = 'equity_facebookpixel_section/facebook_pixel_options/',
        DYNAMIC = 'equity_facebookpixel_section/dynamic_products/';


    public function getFacebookPixelId($store = null) {
        return Mage::getStoreConfig(self::ID . 'facebook_pixel_id', $store);
    }

    public function isFacebookPixelEnabled($store = null) {
        return Mage::getStoreConfigFlag(self::ID . 'enabled', $store);
    }

    public function getProductTrackType($store = null){
        return Mage::getStoreConfig(self::DYNAMIC . 'track_product_type', $store);
    }

    public function getDynamicProducts($store = null){
        return Mage::getStoreConfig(self::DYNAMIC . 'dynamic_products_enabled', $store);
    }

    public function isInitiateCheckoutEnabled($store = null){
        return Mage::getStoreConfigFlag(self::OPTIONS . 'initiatecheckout_enabled', $store);
    }

    public function isAddToCartEnabled($store = null) {
        return Mage::getStoreConfigFlag(self::OPTIONS . 'addtocart_enabled', $store);
    }

    public function isLeadEnabled($store = null) {
        return Mage::getStoreConfigFlag(self::OPTIONS . 'lead_enabled', $store);
    }

    public function isPurchaseEnabled($store = null) {
        return Mage::getStoreConfigFlag(self::OPTIONS . 'purchase_enabled', $store);
    }

    public function isSearchEnabled($store = null) {
        return Mage::getStoreConfigFlag(self::OPTIONS . 'search_enabled', $store);
    }

    public function isCompleteRegistrationEnabled($store = null){
        return Mage::getStoreConfigFlag(self::OPTIONS . 'completeregistration_enabled', $store);
    }

    public function isAddToWishlistEnabled($store = null){
        return Mage::getStoreConfigFlag(self::OPTIONS . 'addtowishlist_enabled', $store);
    }

    public function isAddPaymentInfoEnabled($store = null){
        return Mage::getStoreConfigFlag(self::OPTIONS . 'addpaymentinfo_enabled', $store);
    }

    public function isViewContentEnabled($store = null){
        return Mage::getStoreConfigFlag(self::OPTIONS . 'viewcontent_enabled', $store);
    }
}
