<?php

class Trustpilot_Reviews_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_TRUSTPILOT_GENERAL = 'trustpilot/trustpilot_general_group/';
    const XML_PATH_TRUSTPILOT_TRUSTBOX = 'trustpilot_trustbox/trustpilot_trustbox_group/';

    public function getGeneralConfigValue($value)
    {
        if ($this->getStoreId()) {
            return $this->getGeneralConfigValueByStore($value, self::getStoreId());
        }
        return $this->getGeneralConfigValueByWebsite($value, self::getWebsiteId());
    }

    public function getTrustboxConfigValue($value)
    {
        if ($this->getStoreId()) {
            return $this->getTrustboxConfigValueByStore($value, self::getStoreId());
        }
        return $this->getTrustboxConfigValueByWebsite($value, self::getWebsiteId());
    }

    public function getTrustboxConfigValueByStore($value, $storeId)
    {
        return Mage::getStoreConfig(self::XML_PATH_TRUSTPILOT_TRUSTBOX . $value, $storeId);
    }

    public function getGeneralConfigValueByStore($value, $storeId)
    {
        return Mage::getStoreConfig(self::XML_PATH_TRUSTPILOT_GENERAL . $value, $storeId);
    }

    public function getTrustboxConfigValueByWebsite($value, $storeId)
    {
        return Mage::app()->getWebsite(self::getWebsiteId())->getConfig(self::XML_PATH_TRUSTPILOT_TRUSTBOX . $value);
    }

    public function getGeneralConfigValueByWebsite($value, $storeId)
    {
        return Mage::app()->getWebsite(self::getWebsiteId())->getConfig(self::XML_PATH_TRUSTPILOT_GENERAL . $value);
    }

    public function getStoreIdOrDefault(){
        $storeId = $this->getStoreId();
        if ($storeId) {
            return $storeId;
        }
        return Mage::app()->getWebsite(self::getWebsiteId())->getDefaultStore()->getId();
    }

    public function getStoreId()
    {
        // user at store
        $storeId = Mage::app()->getStore()->getStoreId();
        if ($storeId) {
            return $storeId;
        }
        // user at admin store level
        if (strlen($code = Mage::app()->getRequest()->getParam('store'))) {
            if(($storeId = Mage::getModel('core/store')->load($code)->getId())){
                return $storeId;
            };
        }
        if (strlen($code = $code = Mage::app()->getRequest()->getParam('website'))) {
            return false;
        }
        // user at admin default level
        return 0;
    }

    public function getWebsiteId(){
        // user at admin website level
        if (strlen($code = $code = Mage::app()->getRequest()->getParam('website'))) {
            $websiteId = Mage::getModel('core/website')->load($code)->getId();
            return $websiteId;
        }
        return 0;
    }

    public function getTrustBoxConfig()
    {
        $snippet    = trim($this->getTrustBoxConfigValue('trustpilot_code_snippet'));
        $position   = trim($this->getTrustBoxConfigValue('trustpilot_position'));
        $xpath      = trim($this->getTrustBoxConfigValue('trustpilot_trustbox_xpath'));

        $data = array(
            'snippet'   => base64_encode($snippet),
            'position'  => $position,
            'xpath'     => base64_encode($xpath)
        );

        return $data; 
    }
}
