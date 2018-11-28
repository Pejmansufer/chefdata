<?php

class Trustpilot_Reviews_Block_Trustbox extends Mage_Core_Block_Template
{
    protected $_helper;

    public function __construct()
    {
        $this->_helper = Mage::helper('trustpilot/Data');
        parent::__construct();
    }

    public function getTrustBoxPage($block)
    {
        $page = trim($this->_helper->getTrustBoxConfigValue('trustpilot_trustbox_page'));
        return strcmp($page, $block) === 0 ? 'true' : 'false';
    }
    
    public function getTrustBoxConfig() {
        $data = $this->_helper->getTrustBoxConfig();
        $current_product = Mage::registry('current_product');
        if ($current_product) {
            $sku = $current_product->getSku();
            $data['sku'] = $sku;
        }
        return json_encode($data, JSON_HEX_APOS);
    }
    
    public function getTrustBoxStatus()
    {
        return trim($this->_helper->getTrustBoxConfigValue('trustpilot_trustbox'));
    }
}
