<?php
class Extendware_EWLayeredNav_Helper_Override_Mage_Core_Url extends Extendware_EWLayeredNav_Helper_Override_Mage_Core_Url_Bridge
{
	public function getCurrentUrl()
    {
        $currentUrl = Mage::app()->getRequest()->getParam('ewlayerednav_current_url');
        if (!$currentUrl or $this->isUrlInternal($currentUrl) === false) {
        	$currentUrl = parent::getCurrentUrl();
        }
        
        return $currentUrl;
    }
    
    protected function isUrlInternal($url)
    {
        if (strpos($url, 'http') !== false) {
            if ((strpos($url, Mage::app()->getStore()->getBaseUrl()) === 0) || (strpos($url, Mage::app()->getStore()->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK, true)) === 0)) {
                return true;
            }
        }
        return false;
    }
    
}