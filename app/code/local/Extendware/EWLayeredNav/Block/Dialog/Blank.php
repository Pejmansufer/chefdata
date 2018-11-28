<?php
class Extendware_EWLayeredNav_Block_Dialog_Blank extends Extendware_EWLayeredNav_Block_Dialog_Abstract
{
	protected $messages = array();
	
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewlayerednav/dialog/blank.phtml');
    }
}