<?php
class Extendware_EWCart_Block_Dialog_Cart_Add_Redirect extends Extendware_EWCart_Block_Dialog_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewcart/dialog/cart/add/redirect.phtml');
    }
}