<?php
class Extendware_EWCart_Block_Dialog_Wishlist_Add_Success extends Extendware_EWCart_Block_Dialog_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewcart/dialog/wishlist/add/success.phtml');
    }
}