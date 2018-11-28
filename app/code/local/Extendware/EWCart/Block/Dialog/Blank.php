<?php
class Extendware_EWCart_Block_Dialog_Blank extends Extendware_EWCart_Block_Dialog_Abstract
{
	protected $messages = array();
	
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewcart/dialog/blank.phtml');
    }
}