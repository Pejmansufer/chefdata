<?php
class Extendware_EWCart_Block_Dialog_Cart_Add_Success extends Extendware_EWCart_Block_Dialog_Abstract
{
    public function __construct()
    {
        parent::__construct();
        if ($this->getIsInCart() === false) {
        	$this->setTemplate('extendware/ewcart/dialog/cart/add/success.phtml');
        } else {
        	$this->setTemplate('extendware/ewcart/dialog/cart/add/success_reload.phtml');
        }
    }
}