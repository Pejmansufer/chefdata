<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */ 
class Amasty_Acart_Model_Canceled extends Mage_Core_Model_Abstract
{
    const REASON_ELAPSED = 'elapsed';
    const REASON_BOUGHT = 'bought';
    const REASON_LINK = 'link';
    const REASON_ANY_PRODUCT_OUT_OF_STOCK = 'any_product_out_of_stock';
    const REASON_ALL_PRODUCTS_OUT_OF_STOCK = 'all_products_out_of_stock';
    const REASON_BALCKLIST = 'blacklist';
    const REASON_ADMIN = 'admin';
    const REASON_UPDATED = 'updated';
    const REASON_QUOTE = 'quote';
    const REASON_NOT_ACTIVE = 'is_not_active';
    const REASON_QUOTE_NOT_VALIDATE = 'quote_not_validate';

    public function _construct()
    {
        parent::_construct();
        $this->_init('amacart/canceled');
    }
}