<?php
class Extendware_EWQuickView_Block_Dialog_Product_View_Js extends Extendware_EWCore_Block_Mage_Core_Template
{
	public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewquickview/dialog/product/view/js.phtml');
    }
    
 	public function getProduct() {
 		return Mage::registry('product');
 	}
}