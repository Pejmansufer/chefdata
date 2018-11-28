<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */
class Trmmarketing_Promotionalpopup_Model_Mysql4_Promotionalpopup extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the popup_id refers to the key field in your database table.
        $this->_init('promotionalpopup/promotionalpopup', 'promotionalpopup_id');
    }
}