<?php
class Extendware_EWQuickView_Block_Dialog_Product_View_Error extends Extendware_EWQuickView_Block_Dialog_Error
{
	protected $messages = array();
	
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewquickview/dialog/product/view/error.phtml');
    }
}