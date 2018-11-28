<?php

class Trustpilot_Reviews_Block_Header extends Mage_Core_Block_Template
{
    protected $_scriptUrl;
    protected $_tbWidgetScriptUrl;
    protected $_helper;
    
    public function __construct()
    {
        $this->_helper                  = Mage::helper('trustpilot/Data');
        $this->_scriptUrl               = $this->_helper->getGeneralConfigValue('ScriptUrl');
        $this->_tbWidgetScriptUrl       = $this->_helper->getGeneralConfigValue('WidgetUrl');
        parent::__construct();
    }
    
    public function getScriptUrl()
    {
        return $this->_scriptUrl;
    }

    public function getWidgetScriptUrl()
    {
        return $this->_tbWidgetScriptUrl;
    }

    public function getWgxpathUrl()
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS) . 'trustpilot/wgxpath.install.js';
    }
    

    public function getInstallationKey()
    {
        return trim($this->_helper->getGeneralConfigValue('trustpilot_key'));
    }

    public function getTrustBoxStatus()
    {
        return trim($this->_helper->getTrustBoxConfigValue('trustpilot_trustbox'));
    }
}