<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


class Amasty_Acart_Block_Quote_Totals extends Mage_Checkout_Block_Cart_Totals
{
    public function getQuote()
    {
        return $this->getData('quote');
    }
}
