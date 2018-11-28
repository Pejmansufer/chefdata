<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */
class Amasty_Acart_Model_Observer
{
    function onSalesOrderPlaceAfter($observer)
    {
        $order = $observer->getOrder();
        $quote = $order->getQuote();
        if ($quote) {
            Mage::getModel('amacart/schedule')->buyQuote($quote);
        }
    }

    function clearCoupons()
    {
        $allCouponsCollection = Mage::getModel('salesrule/rule')->getCollection();

        $allCouponsCollection->join(
            array('history' => 'amacart/history'),
            'main_table.rule_id = history.sales_rule_id',
            array('history.history_id')
        );

        $allCouponsCollection->getSelect()->where(
            'main_table.to_date < "' . date('Y-m-d', time()) . '"'
        );

        foreach ($allCouponsCollection->getItems() as $aCoupon) {
            $aCoupon->delete();
        }
    }

    /**
     * @return void()
     */
    function refreshHistory()
    {
        Mage::helper('amacart')->refreshAndProcessHistory();
    }

    /**
     * Append rule product attributes to select by quote item collection
     *
     * @param Varien_Event_Observer $observer
     */
    public function addProductAttributes(Varien_Event_Observer $observer)
    {
        // @var Varien_Object
        $attributesTransfer = $observer->getEvent()->getAttributes();

        $attributes = Mage::getResourceModel('amacart/rule')->getAttributes();

        $result = array();
        foreach ($attributes as $code) {
            $result[$code] = true;
        }

        $attributesTransfer->addData($result);
    }

    public function onSalesruleValidatorProcess($observer)
    {
        $ret = true;
        $ruleId = $observer->getEvent()->getRule()->getRuleId();

        $history = null;

        $historyCollection = Mage::getModel("amacart/history")->getCollection()
            ->addFieldToFilter("sales_rule_id", $ruleId);

        foreach ($historyCollection as $item) {
            if ($item->getCouponCode() == $observer->getEvent()->getRule()->getCode()) {
                $history = $item;
                break;
            }
        }

        if ($history && $history->getId()) {
            $customerEmail = $history->getCustomerId()
                ?
                $observer->getEvent()->getQuote()->getCustomer()->getEmail()
                :
                $observer->getEvent()->getQuote()->getBillingAddress()->getEmail();

            $customerCoupon = Mage::getStoreConfig("amacart/general/customer_coupon");

            if ($customerCoupon && $customerEmail != $history->getEmail()) {
                $observer->getEvent()->getQuote()->setCouponCode("");
            }
        }

        return $ret;
    }

    /**
     * @return bool
     */
    public function logClean()
    {
        /** @var \Amasty_Acart_Model_Log_Xml_Reader $logReaderModel */
        $logReaderModel = Mage::getModel('amacart/log_xml_reader');
        $logReaderModel->cleanLogByTime();

        return true;
    }
}
