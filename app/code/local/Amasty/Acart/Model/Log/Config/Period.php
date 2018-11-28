<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


class Amasty_Acart_Model_Log_Config_Period extends Mage_Core_Model_Config_Data
{
    /**
     * @return $this
     */
    public function _afterLoad()
    {
        $quoteLifetimes = Mage::getConfig()->getStoresConfigByPath('checkout/cart/delete_quote_after');

        if (is_array($quoteLifetimes)) {
            $quoteLifetimes = array_shift($quoteLifetimes);
        }

        if (!$this->getValue()) {
            $this->setValue($quoteLifetimes);
        }

        return $this;
    }
}
