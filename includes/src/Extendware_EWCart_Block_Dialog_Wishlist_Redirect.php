<?php
class Extendware_EWCart_Block_Dialog_Wishlist_Redirect extends Extendware_EWCart_Block_Dialog_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewcart/dialog/wishlist/redirect.phtml');
    }
}