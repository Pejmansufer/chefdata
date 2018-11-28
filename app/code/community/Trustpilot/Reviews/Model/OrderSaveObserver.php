<?php

class Trustpilot_Reviews_Model_OrderSaveObserver
{   
    private $_helper;
    protected $_version;

    public function __construct()
	{
        $this->_version         = '1.0.234';
        $this->_helper          = Mage::helper('trustpilot/Data');
        $this->_orderData       = Mage::helper('trustpilot/OrderData');
        $this->_apiClient       = Mage::helper('trustpilot/TrustpilotHttpClient');
	}
    
    public function execute(Varien_Event_Observer $observer) 
    {
      
        $event = $observer->getEvent();
        $order = $event->getOrder();
        $customerData = $order->getData();
        $orderStatus = $order->getState();  
        $storeId = $order->getStoreId();
        $key = trim($this->_helper->getGeneralConfigValueByStore('trustpilot_key', $storeId));
        
        try {
            $data = array(
                'referenceId' => $order->getIncrementId(),
                'source' => 'Magento-'.Mage::getVersion(),
                'pluginVersion' => $this->_version,
                'orderStatusId' => $orderStatus ,
                'orderStatusName' => $orderStatus,
                'hook' => 'sales_order_save_after'
            );

            if ($orderStatus == Mage_Sales_Model_Order::STATE_NEW || $orderStatus == null) {
                $data['recipientEmail'] = trim($this->_orderData->tryGetEmail($order));
                $data['recipientName'] =  $order->getCustomerName();
                $response = $this->_apiClient->postInvitation($key, $data);

                if ($response['code'] == '202') {
                    
                    $products = $this->_orderData->getProducts($order);
                    $data['products'] = $products;
                    $data['productSkus'] = $this->_orderData->getSkus($products);
                    $this->_apiClient->postInvitation($key, $data);
                }
            } else {
                $data['payloadType'] = 'OrderStatusUpdate';
                $this->_apiClient->postInvitation($key, $data);
            }
            return;
        } catch (Exception $e) {
            $error = array('message' => $e->getMessage());
            $data = array('error' => $error);
            $this->_apiClient->postInvitation($key, $data);
            return;
        }     
    }


}