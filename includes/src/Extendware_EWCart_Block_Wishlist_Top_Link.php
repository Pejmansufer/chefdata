<?php
class Extendware_EWCart_Block_Wishlist_Top_Link extends Extendware_EWCore_Block_Mage_Core_Template
{
	public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewcart/wishlist/top/link.phtml');
    }
}