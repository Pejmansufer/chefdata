<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */
class Amasty_Acart_Model_Schedule extends Mage_Core_Model_Abstract
{
    const EMAIL_TEMPLATE_XML_PATH = 'amacart/template/main_template';
    const NAME_XML_PATH = 'amacart/template/name';
    const EMAIL_XML_PATH = 'amacart/template/email';
    const CC_XML_PATH = 'amacart/template/cc';
    const REPLY_TO_NAME_XML_PATH = 'amacart/template/reply_to_name';
    const REPLY_TO_EMAIL_XML_PATH = 'amacart/template/reply_to_email';
    const ABANDONED_GAP_TIME_XML_PATH = 'amacart/debug/abandoned_gap_time';

    const LAST_EXECUTED_PATH = 'amacart/common/last_executed';

    const DEFAULT_TEMPLATE_CODE = 'Amasty: Abandoned Cart Reminder';

    const AMASTY_TEST_ABANDONED_GAP = 10;

    protected static $_actualGap = 172800; //2 days
    protected static $_abandonedGap = 0; //10 minutes
    protected $_customerLog = array();
    protected $_customerGroup = array();
    protected $_rules = array();

    /**
     * @var array
     */
    protected $logData = array();

    public function _construct()
    {
        $this->_init('amacart/schedule');
        $this->initAbandonedGapTime();
    }

    function getDays()
    {
        return $this->getDelayedStart() > 0
            ?
            floor($this->getDelayedStart() / 24 / 60 / 60)
            :
            null;
    }

    /**
     * @return $this
     */
    protected function initAbandonedGapTime()
    {
        $abandonedTime = Mage::getStoreConfig(self::ABANDONED_GAP_TIME_XML_PATH) ?: 600;
        self::$_abandonedGap = $abandonedTime;

        return $this;
    }

    function getHours()
    {
        $days = $this->getDays();
        $time = $this->getDelayedStart() - ($days * 24 * 60 * 60);

        return $time > 0
            ?
            floor($time / 60 / 60)
            :
            null;
    }

    function getMinutes()
    {
        $days = $this->getDays();
        $hours = $this->getHours();
        $time = $this->getDelayedStart() - ($days * 24 * 60 * 60) - ($hours * 60 * 60);

        return $time > 0
            ?
            floor($time / 60)
            :
            null;
    }

    /**
     * @return void
     */
    public function run()
    {
        $this->resetLogData();

        $this->_prepare();
        $this->_process();
        $this->_checkCanceledQuotes();

        $this->writeLog();
    }

    /**
     * @return bool
     */
    protected function resetLogData()
    {
        $this->logData = array(
            'time' => $this->date(time()),
            'schedule' => array(),
            'send' => array()
        );

        return true;
    }

    /**
     * @return bool
     */
    protected function writeLog()
    {
        /** @var \Amasty_Acart_Model_Log_Xml_Writer $logWriter */
        $logWriter = Mage::getModel('amacart/log_xml_writer');
        $logWriter->writeLogFile($this->logData);

        return true;
    }

    protected function _sendEmail($history)
    {
        $templateId = 'amacart_template_email';
        $storeId = $history->getStoreId();

        $vars = array(
            'history' => $history
        );

        $sender = array(
            'name'  => Mage::getStoreConfig(self::NAME_XML_PATH, $history->getStoreId()),
            'email' => Mage::getStoreConfig(self::EMAIL_XML_PATH, $history->getStoreId())
        );

        /** @var Mage_Core_Model_Email_Template $mail */
        $mail = Mage::getModel('core/email_template');

        $ccConf = str_replace(' ', '', Mage::getStoreConfig(self::CC_XML_PATH, $history->getStoreId()));
        $cc = explode(',', $ccConf);

        if ($ccConf && !empty($cc)) {
            $mail->addBcc($cc);
        }

        if ($replyToEmail = trim(Mage::getStoreConfig(self::REPLY_TO_EMAIL_XML_PATH))) {

            $replyToName = trim(Mage::getStoreConfig(self::REPLY_TO_NAME_XML_PATH));

            $mail->getMail()->setReplyTo(
                $replyToEmail, $replyToName ? $replyToName : null
            );
        }
        $this->setSentDate($history);

        $mail->sendTransactional($templateId, $sender, $history->getEmail(), $history->getCustomerName(), $vars, $storeId);
    }

    /**
     * @param $history
     */
    protected function setSentDate($history)
    {
        if ($history->getId()) {
            $history->setFinishedAt($this->date(time()));
            $history->save();
        }
    }

    protected function _process()
    {
        $historyCollection = Mage::getModel('amacart/history')->getCollection();

//        $historyCollection->addQuoteData();

        $historyCollection->addFieldToFilter('scheduled_at', array('lteq' => $this->date(time())));
        $historyCollection->addFieldToFilter('status', array('eq' => Amasty_Acart_Model_History::STATUS_PENDING));

        foreach ($historyCollection as $history) {
            $this->processHistoryItem($history);
        }
    }

    protected function _getRule($history)
    {
        if (!array_key_exists($history->getRuleId(), $this->_rules)) {

            $this->_rules[$history->getRuleId()] = Mage::getModel('amacart/rule')->load($history->getRuleId());
        }
        return $this->_rules[$history->getRuleId()];
    }

    /**
     * @param                        $history
     * @param Mage_Sales_Model_Quote $quote
     *
     * @return bool
     */
    protected function _cancel($history, $quote)
    {
        $reason = null;
        $rule = $this->_getRule($history);

        if (!$quote) {
            $reason = Amasty_Acart_Model_Canceled::REASON_QUOTE;
        } else {
            foreach (explode(',', $rule->getCancelRule()) as $cancelRule) {
                switch ($cancelRule) {
                    case Amasty_Acart_Model_Rule::CANCEL_RULE_ALL_PRODUCTS_OUT_OF_STOCK:
                        $hasInStock = false;
                        /** @var Mage_Sales_Model_Quote_Item $item */
                        foreach ($quote->getAllItems() as $item) {
                            $stockItem = $this->_getStockItem($item);
                            if ($stockItem && $stockItem->getIsInStock()) {
                                $hasInStock = true;
                                break;
                            }
                        }

                        if (!$hasInStock) {
                            $reason = Amasty_Acart_Model_Canceled::REASON_ALL_PRODUCTS_OUT_OF_STOCK;
                        }
                        break;
                    case Amasty_Acart_Model_Rule::CANCEL_RULE_ANY_PRODUCT_OUT_OF_STOCK:
                        /** @var Mage_Sales_Model_Quote_Item $item */
                        foreach ($quote->getAllItems() as $item) {
                            $stockItem = $this->_getStockItem($item);

                            if ($stockItem && !$stockItem->getIsInStock()) {
                                $reason = Amasty_Acart_Model_Canceled::REASON_ANY_PRODUCT_OUT_OF_STOCK;
                                break;
                            }
                        }
                        break;
                }
            }
        }

        if (!$rule->getIsActive() && ($reason === null)) {
            $reason = Amasty_Acart_Model_Canceled::REASON_NOT_ACTIVE;
        }

        if (($reason === null) && !$rule->validate($quote)) {
            $reason = Amasty_Acart_Model_Canceled::REASON_QUOTE_NOT_VALIDATE;
        }

        if ($reason !== null) {
            $canceled = $this->_cancelQuote(
                $history->getQuoteId(),
                $history->getId(),
                $reason,
                false
            );

            $history->setStatus(Amasty_Acart_Model_History::STATUS_DONE);
            $history->setCanceledId($canceled->getId());
            $history->save();
        }

        return $reason !== null;
    }

    protected function _getStockItem($quoteItem)
    {
        if (!$quoteItem || !$quoteItem->getProductId() || !$quoteItem->getQuote()
            || $quoteItem->getQuote()->getIsSuperMode()
            || $quoteItem->getProduct()->getTypeId() == 'configurable'
        ) {
            return false;
        }

        return $quoteItem->getProduct()->getStockItem();
    }

    /**
     * @param $history
     */
    function processHistoryItem($history)
    {
        $quote = Mage::helper('amacart')->loadQuote($history->getQuoteId());
        $scheduleId = $history->getScheduleId();

        $schedule = Mage::getModel('amacart/schedule')->load($scheduleId);

        if (!$this->_cancel($history, $quote)) {
            $this->_setupEmailMessage($schedule, $history, $quote);
            $history->setExecutedAt($this->date(time()));
            $history->setStatus(Amasty_Acart_Model_History::STATUS_PROCESSING);
            $history->save();

            if ($this->_sendEmail($history)) {
                $history->setFinishedAt($this->date(time()));
                $history->setStatus(Amasty_Acart_Model_History::STATUS_SENT);
                $history->save();
            }
        }
    }

    protected function _cancelQuote($quoteId, $historyId, $status, $override = false)
    {
        $canceled = Mage::getModel('amacart/canceled')->load($quoteId, 'quote_id');

        if ($override || $canceled->getId() == null) {
            $canceled->setData(
                array(
                    'canceled_id' => $canceled->getId(),
                    'quote_id'    => $quoteId,
                    'history_id'  => $historyId,
                    'created_at'  => $this->date(time()),
                    'reason'      => $status,
                )
            );
            $canceled->save();
        }

        return $canceled;
    }

    protected function _checkCanceledQuotes()
    {
        /*
         * CHECK ELAPSED QUOTES
         */
        $resource = Mage::getSingleton('core/resource');

        $pendingCollection = Mage::getModel('amacart/history')->getCollection();

        $pendingCollection->addFieldToFilter('status', array('eq' => Amasty_Acart_Model_History::STATUS_PENDING));
        $pendingIds = array();

        foreach ($pendingCollection->getData() as $item) {
            $pendingIds[] = $item['quote_id'];
        }

        $canceledCollection = Mage::getModel('amacart/history')->getCollection();

        $canceledCollection->addCanceledData();

        if (count($pendingIds) > 0) {
            $canceledCollection->addFieldToFilter('main_table.quote_id', array('nin' => $pendingIds));
        }

        foreach ($canceledCollection as $history) {
            $canceled = $this->_cancelQuote(
                $history->getQuoteId(),
                null,
                Amasty_Acart_Model_Canceled::REASON_ELAPSED,
                false
            );

            $history->setCanceledId($canceled->getId());
            $history->save();
        }
        /*
         * CHECK BLACK LIST QUOTES
         */
        $blacklistCollection = Mage::getModel('amacart/history')->getCollection();
        $blacklistCollection->addBlacklistData();

        $blacklistCollection->addFieldToFilter('status', array('eq' => Amasty_Acart_Model_History::STATUS_PENDING));

        foreach ($blacklistCollection as $history) {
            $canceled = $this->_cancelQuote(
                $history->getQuoteId(),
                null,
                Amasty_Acart_Model_Canceled::REASON_BALCKLIST,
                true
            );

            $history->setStatus(Amasty_Acart_Model_History::STATUS_BLACKLIST);
            $history->setCanceledId($canceled->getId());
            $history->save();
        }
    }

    /**
     * @param bool $amastyTest
     * @return object
     */
    protected function _getQuoteCollection($amastyTest = false)
    {
        $gt = $this->getLastExecuted();

        if (!$amastyTest) {
            $lt = time() - self::$_abandonedGap;
//          $lt = Mage::getModel('core/date')->timestamp() - self::$_abandonedGap;
            $this->setLastExecuted($lt);
        } else {
            $lt = time() - self::AMASTY_TEST_ABANDONED_GAP;
        }

        $resource = Mage::getSingleton('core/resource');
        $quoteCollection = Mage::getModel('sales/quote')->getCollection();
        $quoteExpiresOn = Mage::getStoreConfig("amacart/quote/expires_on");
        $quoteJoin = 'main_table.entity_id = canceled.quote_id';

        if ($quoteExpiresOn && $quoteExpiresOn > 0) {
            $quoteJoin .= ' and canceled.created_at > "' . $this->date(time() - 60 * 60 * 24 * $quoteExpiresOn) . '"';
        }

        $quoteCollection->getSelect()->joinLeft(
            array('canceled' => $resource->getTableName('amacart/canceled')),
            $quoteJoin,
            array('canceled.canceled_id')
        );

        $quoteCollection->getSelect()->joinLeft(
            array('quote2email' => $resource->getTableName('amacart/quote2email')),
            'main_table.entity_id = quote2email.quote_id',
            array('ifnull(main_table.customer_email, quote2email.email) as target_email')
        );

        $quoteCollection->getSelect()->joinLeft(
            array('history' => $resource->getTableName('amacart/history')),
            'main_table.entity_id = history.quote_id',
            array('history.quote_id')
        );

        $quoteCollection->getSelect()->group('main_table.entity_id');

        $quoteCollection->addFieldToFilter('history.history_id', array('null' => true));
        $quoteCollection->addFieldToFilter('canceled.canceled_id', array('null' => true));
        $quoteCollection->addFieldToFilter('main_table.updated_at', array('gt' => $this->date($gt)));
        $quoteCollection->addFieldToFilter('main_table.updated_at', array('lt' => $this->date($lt)));
        $quoteCollection->addFieldToFilter('main_table.is_active', array('eq' => 1));
        $quoteCollection->addFieldToFilter('main_table.items_count', array('gt' => 0));

        $quoteCollection->getSelect()->where('IFNULL(main_table.customer_email, quote2email.email) is not null');

        if ($amastyTest) {
            $emailDomains = Mage::helper('amacart')->getDebugEnabledEmailDomains();
            if ($emailDomains && is_array($emailDomains)) {

                $prepare = function ($n) {
                    return array('like' => '%@' . $n . '%');
                };

                $quoteCollection->addFieldToFilter('main_table.customer_email',
                    array(array_map($prepare, $emailDomains)));
            }
        }

        if (Mage::getStoreConfig("amacart/general/only_customers") && !$amastyTest) {
            $quoteCollection->addFieldToFilter('main_table.customer_id', array('notnull' => true));
        }

        return $quoteCollection;
    }

    protected function _getScheduleCollection($rule)
    {
        $scheduleCollection = Mage::getModel('amacart/schedule')->getCollection();

        $scheduleCollection->addFilter('rule_id', $rule->getId());

        return $scheduleCollection;
    }

    protected function _getRuleCollection()
    {
        $ruleCollection = Mage::getModel('amacart/rule')->getCollection();
        $ruleCollection->addFilter('is_active', 1);
        $ruleCollection->setOrder('priority', 'DESC');

        return $ruleCollection;
    }


    protected function _prepare()
    {
        $amastyTest = Mage::helper('amacart')->isDebugMode();
        $ruleCollection = $this->_getRuleCollection();
        $quoteCollection = $this->_getQuoteCollection($amastyTest);

        $this->logData['schedule']['allAbandonedCart'] = array(
            'ids' => implode(',', $quoteCollection->getAllIds()),
            'count' => $quoteCollection->getSize()
        );

        $completedQuotes = array();

        foreach ($quoteCollection as $quote) {
            foreach ($ruleCollection as $rule) {
                if (!in_array($quote->getId(), $completedQuotes) && $rule->validate($quote)) {
                    $this->_checkUpdated($quote);

                    $scheduleCollection = $this->_getScheduleCollection($rule);

                    foreach ($scheduleCollection as $schedule) {
                        Mage::app()->setCurrentStore($quote->getStoreId());
                        $delayedStart = $schedule->getDelayedStart() > 0 ? $schedule->getDelayedStart() : (5 * 60);
                        $this->createHistoryItem($quote, $schedule,
                            ($amastyTest ? self::AMASTY_TEST_ABANDONED_GAP : $delayedStart));
                    }

                    $completedQuotes[] = $quote->getId();
                }
            }
        }

        $this->logData['schedule']['validAbandonedCart'] = array(
            'ids' => implode(',', $completedQuotes),
            'count' => count($completedQuotes)
        );
    }

    protected function _getCoupon($rule, $schedule)
    {
        $coupon = array(
            'code' => null,
            'id'   => null
        );

        if ($schedule->getUseRule()) {
            $generator = $rule->getCouponMassGenerator();
            $generator->setData(
                array(
                    'rule_id'           => $rule->getId(),
                    'qty'               => 1,
                    'length'            => 12,
                    'format'            => 'alphanum',
                    'prefix'            => '',
                    'suffix'            => '',
                    'dash'              => '0',
                    'uses_per_coupon'   => '0',
                    'uses_per_customer' => '0',
                    'to_date'           => '',
                )
            );
            $generator->generatePool();
            $generated = $generator->getGeneratedCount();

            $collection = Mage::getResourceModel('salesrule/coupon_collection');

            $collection
                ->addFieldToFilter('main_table.rule_id', $rule->getId())
                ->getSelect()
                ->joinLeft(
                    array('history' => Mage::getSingleton('core/resource')->getTableName('amacart/history')),
                    'main_table.coupon_id = history.coupon_id',
                    array()
                )->where('history.history_id is null')
                ->order('main_table.coupon_id desc')
                ->limit(1);

            $items = $collection->getItems();

            if (count($items) > 0) {
                $salesCoupon = end($items);

                $coupon['id'] = $salesCoupon->getId();
                $coupon['code'] = $salesCoupon->getCode();

                $expirationDate = new Zend_Date(strtotime($salesCoupon->getExpirationDate()));

                $coupon['expiration_date'] = $expirationDate->toString('dd MMM, yyyy');
            }

        } else {
            if ($rule) {
                $coupon['code'] = $rule->getCouponCode();
                $coupon['expiration_date'] = $rule->getToDate();
            }
        }

        return $coupon;
    }

    function createHistoryItem($quote, $schedule, $delayedStart = 0)
    {
        $history = Mage::getModel('amacart/history');

        $history->setData(
            array(
                'quote_id'      => $quote->getId(),
                'store_id'      => $quote->getStoreId(),
                'email'         => $quote->getTargetEmail() ? $quote->getTargetEmail() : $quote->getCustomerEmail(),
                'customer_id'   => $quote->getCustomerId(),
                'customer_name' => $quote->getCustomerFirstname() . ' ' . $quote->getCustomerLastname(),
                'public_key'    => uniqid(),
                'schedule_id'   => $schedule->getId(),
                'rule_id'       => $schedule->getRuleId(),
                'created_at'    => $this->date(time()),
                'scheduled_at'  => $this->date(time() + $delayedStart),
                'status'        => Amasty_Acart_Model_History::STATUS_PENDING
            )
        );

        $history->save();

        return $history;
    }

    protected function _setupEmailMessage($schedule, $history, $quote)
    {
        $rule = $history->getRule($schedule);

        $coupon = $this->_getCoupon($rule, $schedule);

        if ($rule) {
            $history->addData(
                array(
                    'sales_rule_id' => $rule->getId(),
                    'coupon_code'   => $coupon['code'],
                    'coupon_id'     => $coupon['id'],
                    'coupon_expiration_date' => $coupon['expiration_date'],
                    'coupon_discount_amount' => $schedule->getDiscountAmount(),
                )
            );

            $origCouponCode = $quote->getCouponCode();
            try {
                $quote->setCouponCode($coupon['code'])
                    ->collectTotals()
                    ->save();
            } catch (Exception $e) {

            }
        }

        //reinitialize translation
        Mage::app()->getTranslator()->init('adminhtml', true);
        $messages = $this->_getQuoteItemsMessage($schedule->getEmailTemplateId(), $history, $quote, $rule);

        if ($messages) {
            $history->setStatus(Amasty_Acart_Model_History::STATUS_TEMPLATE);
            $history->save();
        }

        if ($rule) {
            try {
                $quote->setCouponCode($origCouponCode)
                    ->collectTotals()
                    ->save();
            } catch (Exception $e) {
            }
        }

        $history->setBody($messages['body']);
        $history->setSubject($messages['subject']);
        $history->save();
    }

    protected function _getCustomer($quote)
    {
        if ($quote->getCustomerId()) {
            $customer = Mage::getModel('customer/customer')->load($quote->getCustomerId());
        } else {
            $customer = new Varien_Object();
        }

        $customer->setFirstname($quote->getCustomerFirstname());
        $customer->setMiddlename($quote->getCustomerMiddlename());
        $customer->setLastname($quote->getCustomerLastname());
        $customer->setSuffix($quote->getCustomerSuffix());

        return $customer;
    }

    protected function _getCouponTotal($total, $items, $type, $amount)
    {
        $coreHelper = Mage::helper('core');

        $ret = $total;

        switch ($type) {
            case "by_percent":
                $ret -= $ret * $amount / 100;
                break;
            case "by_fixed":
                $ret -= count($items) * $amount;
                break;
            case "cart_fixed":
                $ret -= $amount;
                break;
        }

        return $coreHelper->currency($ret, true, false);
    }

    protected function _getTotal($total, $items, $type, $amount)
    {
        $coreHelper = Mage::helper('core');

        $ret = $total;

        return $coreHelper->currency($ret, true, false);
    }

    protected function _initCustomQuoteVars(&$quote, $history)
    {
        $sceduleId = $history->getScheduleId();

        $schedule = Mage::getModel('amacart/schedule')->load($sceduleId);

        $totalWith = $this->_getTotal(
            $quote->getSubtotalWithDiscount(), $quote->getAllVisibleItems(), $schedule->getCouponType(),
            $schedule->getDiscountAmount()
        );
        $totalWithout = $this->_getTotal(
            $quote->getSubtotal(), $quote->getAllVisibleItems(), $schedule->getCouponType(),
            $schedule->getDiscountAmount()
        );

        $quote->setSubtotalWithCoupon($totalWith);
        $quote->setSubtotalWithoutCoupon($totalWithout);

        $tax = $this->_getTax($quote);

        $quote->setSubtotalTaxWithCoupon(
            $this->_getTotal(
                $quote->getSubtotalWithDiscount() + $tax, $quote->getAllVisibleItems(), $schedule->getCouponType(),
                $schedule->getDiscountAmount()
            )
        );
        $quote->setSubtotalTaxWithoutCoupon(
            $quote->getSubtotal() + $tax, $quote->getAllVisibleItems(), $schedule->getCouponType(),
            $schedule->getDiscountAmount()
        );
    }

    protected function _getTax($quote)
    {
        $tax = 0;
        foreach ($quote->getAllVisibleItems() as $item) {
            $tax += $item->getTaxAmount();
        }
        return $tax;
    }

    protected function _getQuoteItemsMessage($templateId, $history, $quote, $coupon)
    {
        $ret = array(
            'body'    => '',
            'subject' => ''
        );

        if ($templateId === null) {
            $templateId = Mage::getStoreConfig(self::EMAIL_TEMPLATE_XML_PATH);
        } //'amacart_notification_sent_template';

        $storeId = $history->getStoreId();

        $this->_initCustomQuoteVars($quote, $history);
        $customer = $this->_getCustomer($quote);

        $vars = $this->_getFormatVars($customer, $history, $quote, $coupon);
        $variables = array(
            'quote'         => $quote,
            'customer'      => $customer,
            'history'       => $history,
            'schedule'      => $this,
            'store'         => Mage::app()->getStore($storeId),
            'urlmanager'    => Mage::getModel('amacart/urlmanager')->init($history),
            'formatmanager' => Mage::getModel('amacart/formatmanager')->init($vars),
            'rule'          => $this->_getRule($history)
        );
        $emailTemplate = Mage::getModel('core/email_template');
        $emailTemplate->setDesignConfig(
            array(
                'area'  => 'frontend',
                'store' => $storeId
            )
        );

        if (is_numeric($templateId)) {
            $emailTemplate->load($templateId);
        } else {
            $localeCode = Mage::getStoreConfig('general/locale/code', $storeId);
            $emailTemplate->loadDefault($templateId, $localeCode);
        }

        if (!$emailTemplate->getId()) {
            throw Mage::exception(
                'Mage_Core', Mage::helper('core')->__('Invalid transactional email code: ' . $templateId)
            );
        }

        try {
            $ret['body'] = $emailTemplate->getProcessedTemplate($variables, true);
            $ret['subject'] = $emailTemplate->getProcessedTemplateSubject($variables);

            return $ret;
        } catch (Exception $e) {
            return false;
        }
    }

    protected function _getFormatVars($customer, $history, $quote, $coupon)
    {
        $logCustomer = $this->_loadCustomerLog($customer);
        $customerGroup = $this->_loadCustomerGroup($customer->getGroupId());
        return array(
            Amasty_Acart_Model_Formatmanager::TYPE_CUSTOMER       => $customer,
            Amasty_Acart_Model_Formatmanager::TYPE_CUSTOMER_GROUP => $customerGroup,
            Amasty_Acart_Model_Formatmanager::TYPE_CUSTOMER_LOG   => $logCustomer,
            Amasty_Acart_Model_Formatmanager::TYPE_HISTORY        => $history,
            Amasty_Acart_Model_Formatmanager::TYPE_QUOTE          => $quote,
            Amasty_Acart_Model_Formatmanager::TYPE_COUPON         => $coupon
        );
    }

    protected function _loadCustomerLog($customer)
    {
        if ($customer->getId()) {
            if (!isset($this->_customerLog[$customer->getId()])) {

                $this->_customerLog[$customer->getId()] = Mage::getModel('log/customer')->load(
                    $customer->getId(), 'customer_id'
                );
                $this->_customerLog[$customer->getId()]->setInactiveDays(
                    floor((time() - strtotime($this->_customerLog[$customer->getId()]->getLoginAt())) / 60 / 60 / 24)
                );
            }
            return $this->_customerLog[$customer->getId()];
        }
        return '';
    }

    protected function _loadCustomerGroup($id)
    {
        if (!isset($this->_customerGroup[$id])) {
            $this->_customerGroup[$id] = Mage::getModel('customer/group')->load($id);
        }

        return $this->_customerGroup[$id];
    }

    function unsubscribe($history)
    {
        $blacklist = Mage::getModel('amacart/blist')->load($history->getEmail(), 'email');

        $blacklist->setData(
            array(
                'blacklist_id' => $blacklist->getId(),
                'email'        => $history->getEmail(),
                'created_at'   => $this->date(time()),
            )
        );
        $blacklist->save();

        $canceled = $this->_cancelQuote(
            $history->getQuoteId(),
            $history->getId(),
            Amasty_Acart_Model_Canceled::REASON_BALCKLIST,
            true
        );

        $otherCollection = Mage::getModel('amacart/history')->getCollection();
        $otherCollection->addFieldToFilter('email', array('eq' => $history->getEmail()));
        $otherCollection->addFieldToFilter('status', array('eq' => Amasty_Acart_Model_History::STATUS_PENDING));

        foreach ($otherCollection as $otherItem) {
            $otherItem->setStatus(Amasty_Acart_Model_History::STATUS_BLACKLIST);
            $otherItem->setCanceledId($canceled->getId());
            $otherItem->save();
        }
    }

    function massCancel($ids)
    {
        $cancelCollection = Mage::getModel('amacart/history')->getCollection();
        $cancelCollection->addFieldToFilter('history_id', array('in' => $ids));
        foreach ($cancelCollection as $cancelItem) {

            $canceled = $this->_cancelQuote(
                $cancelItem->getQuoteId(),
                null,
                Amasty_Acart_Model_Canceled::REASON_ADMIN,
                true
            );

            $cancelItem->setStatus(Amasty_Acart_Model_History::STATUS_DONE);
            $cancelItem->setCanceledId($canceled->getId());
            $cancelItem->save();
        }
    }

    function clickByLink($history)
    {
        $rule = Mage::getModel('amacart/rule')->load($this->getRuleId());
        $cancelRules = explode(',', $rule->getCancelRule());

        if (in_array(Amasty_Acart_Model_Rule::CANCEL_RULE_LINK, $cancelRules)) {

            $canceled = $this->_cancelQuote(
                $history->getQuoteId(),
                $history->getId(),
                Amasty_Acart_Model_Canceled::REASON_LINK,
                true
            );

            $otherCollection = Mage::getModel('amacart/history')->getCollection();
            $otherCollection->addFieldToFilter('email', array('eq' => $history->getEmail()));
            $otherCollection->addFieldToFilter('status', array('eq' => Amasty_Acart_Model_History::STATUS_PENDING));

            foreach ($otherCollection as $otherItem) {
                $otherItem->setStatus(Amasty_Acart_Model_History::STATUS_DONE);
                $otherItem->setCanceledId($canceled->getId());
                $otherItem->save();
            }
        }
    }

    protected function _checkUpdated($quote)
    {
//        $history = Mage::getModel('amacart/history')->load($quoteId, 'quote_id');

        $historyCollection = Mage::getModel('amacart/history')->getCollection();

        $historyCollection->addFieldToFilter('email', array('eq' => $quote->getTargetEmail()));
        $historyCollection->addFieldToFilter('quote_id', array('neq' => $quote->getId()));
        $historyCollection->addFieldToFilter('status', array('eq' => Amasty_Acart_Model_History::STATUS_PENDING));
        $historyCollection->addFieldToFilter('store_id', array('eq' => $quote->getStoreId()));

        if ($historyCollection->getSize() > 0) {
            foreach ($historyCollection as $historyItem) {

                $canceled = $this->_cancelQuote(
                    $historyItem->getQuoteId(),
                    null,
                    Amasty_Acart_Model_Canceled::REASON_UPDATED,
                    true
                );

                $historyItem->setStatus(Amasty_Acart_Model_History::STATUS_DONE);
                $historyItem->setFinishedAt($this->date(time()));
                $historyItem->setCanceledId($canceled->getId());
                $historyItem->save();
            }
        }
    }

    function buyQuote($quote)
    {
//        $history = Mage::getModel('amacart/history')->load($quoteId, 'quote_id');

        $historyCollection = Mage::getModel('amacart/history')->getCollection();

        $historyCollection->addFieldToFilter('email', array('eq' => $quote->getCustomerEmail()));
        $historyCollection->addFieldToFilter('status', array('eq' => Amasty_Acart_Model_History::STATUS_PENDING));

        $canceled = $this->_cancelQuote(
            $quote->getId(),
            null,
            Amasty_Acart_Model_Canceled::REASON_BOUGHT,
            true
        );

        if ($historyCollection->getSize() > 0) {

            foreach ($historyCollection as $historyItem) {
                $historyItem->setStatus(Amasty_Acart_Model_History::STATUS_DONE);
                $historyItem->setFinishedAt($this->date(time()));
                $historyItem->setCanceledId($canceled->getId());
                $historyItem->save();
            }
        }
    }

    function date($timestamp)
    {
        return date('Y-m-d H:i:s', $timestamp);
    }

    function getLastExecuted()
    {
        $ret = (string)Mage::getStoreConfig(self::LAST_EXECUTED_PATH);
        if (empty($ret)) {
            $ret = time() - self::$_actualGap;
        }
        return $ret;
    }

    function setLastExecuted($time)
    {
        Mage::getConfig()->saveConfig(self::LAST_EXECUTED_PATH, $time);
        Mage::getConfig()->cleanCache();
    }

    /**
     * Processing object before delete data
     *
     * @return Amasty_Acart_Model_Schedule
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        if ($this->getOrigData() && $this->_hasModelChanged()) {
            Mage::getModel('amacart/history')->updateHistoryDynamic($this);
        }
    }

    /**
     * Processing object before delete data
     *
     * @return Amasty_Acart_Model_Schedule
     */
    protected function _afterSave()
    {
        parent::_afterSave();
        if (!$this->getOrigData() && $this->isObjectNew()) {
            Mage::getModel('amacart/history')->addNewHistoryDynamic($this);
        }
    }

    /**
     * Processing object before delete data
     *
     * @return Amasty_Acart_Model_Schedule
     */
    protected function _beforeDelete()
    {
        $pendingCollection = Mage::getModel('amacart/history')
            ->getCollection()
            ->addPendingFilter()
            ->addFieldToFilter('schedule_id', array('eq' => $this->getScheduleId()));

        try {
            if ($pendingCollection) {
                foreach ($pendingCollection as $history) {
                    $history->delete();
                }
            }
        } catch (Exception $e) {
            Mage::throwException($e->getMessage());
        }

        return parent::_beforeDelete();
    }
}
