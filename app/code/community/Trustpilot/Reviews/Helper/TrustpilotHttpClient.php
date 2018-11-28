<?php
 
 class Trustpilot_Reviews_Helper_TrustpilotHttpClient
 {
    private $_helper;
    private $_httpClient;

    public function __construct()
    {
        $this->_helper = Mage::helper('trustpilot/Data');
        $this->_httpClient = Mage::helper('trustpilot/HttpClient');
    }

    public function postInvitation($integrationKey, $data = array())
    {
        $url = $this->_helper->getGeneralConfigValue('ApiUrl') . $integrationKey . '/invitation';
        $origin = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB); 
        $httpRequest = "POST";
    
        $response = $this->_httpClient->request(
            $url,
            $httpRequest,
            $origin,
            $data
        );
        return $response;
    }

    public function postSettings($integrationKey, $origin, $data = array())
    {
        $url = $this->_helper->getGeneralConfigValue('ApiUrl') . $integrationKey . '/settings';
        $httpRequest = "POST";
        $response = $this->_httpClient->request(
            $url,
            $httpRequest,
            $origin,
            $data
        );
        return $response;
    }
 }