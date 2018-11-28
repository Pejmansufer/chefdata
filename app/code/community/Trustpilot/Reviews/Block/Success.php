<?php

class Trustpilot_Reviews_Block_Success extends Mage_Checkout_Block_Onepage_Success
{
    protected $_version;
    protected $_helper;
    protected $_notifications;
    protected $_orderData;
    
    public function __construct()
    {
        $this->_version          = '1.0.234';
        $this->_helper           = Mage::helper('trustpilot/Data');
        $this->_orderData        = Mage::helper('trustpilot/OrderData');
        $this->_notifications    = Mage::helper('trustpilot/Notifications');
        parent::__construct();
    }

    public function getOrder()
    {
        try {
            $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
            $order = Mage::getModel('sales/order')->load($orderId);

            $products = $this->_orderData->getProducts($order);
            
            $data = array(
                'recipientEmail' => trim($this->getEmail($order)),
                'recipientName' => $order->getCustomerName(),
                'referenceId' => $order->getIncrementId(),
                'productSkus' => $this->_orderData->getSkus($products),
                'source' => 'Magento-'.Mage::getVersion(),
                'pluginVersion' => $this->_version,
                'products' => $products,
            );
            return json_encode($data, JSON_HEX_APOS);
        } catch (Exception $e) {
            $error = array('message' => $e->getMessage());
            $data = array('error' => $error);
            return json_encode($data, JSON_HEX_APOS);
        }
    }

    public function getLastRealOrder()
    {
        $orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();

        return Mage::getModel('sales/order')->loadByIncrementId($orderId);
    }

    public function getEmail($order)
    {
        $email = $this->_orderData->tryGetEmail($order);

        if (!($this->_orderData->isEmpty($email)))
            return $email;
        
        $order = $this->getLastRealOrder();
        
        return $this->_orderData->tryGetEmail($order);
    }

    public function checkInstallationKey()
    {
        $key = trim($this->_helper->getGeneralConfigValue('trustpilot_key'));
       
        if ($this->_orderData->isEmpty($key))
            $this->addNotification();
    }

    public function addNotification()
    {
        if ($this->_orderData->isEmpty($this->_notifications->getLatestMissingKeyNotification()))
            $this->_notifications->createMissingKeyNotification();
    }

    
}
