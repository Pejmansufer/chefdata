<?php
class Extendware_EWLayeredNav_Block_Js_General extends Extendware_EWCore_Block_Generated_Js
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewlayerednav/js/general.phtml');
    }
    
	public function getCacheKey() {
        $key = parent::getCacheKey();
        $key .= $this->mHelper('config')->getHash();

        return md5($key);
	}
}

