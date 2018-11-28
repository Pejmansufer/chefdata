<?php
class Extendware_EWCart_Block_Js_General extends Extendware_EWCore_Block_Generated_Js
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewcart/js/general.phtml');
    }
    
	public function getConfirmationJs($message = '')
    {
    	return $message ? 'confirm(' . json_encode($message) . ')' : '1';
    }
    
	public function getCacheKey() {
        $key = parent::getCacheKey();
        $key .= $this->mHelper('config')->getHash();
        
        return md5($key);
	}
}

