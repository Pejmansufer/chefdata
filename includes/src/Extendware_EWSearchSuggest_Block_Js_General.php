<?php
class Extendware_EWSearchSuggest_Block_Js_General extends Extendware_EWCore_Block_Generated_Js
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewsearchsuggest/js/general.phtml');
    }
    
	public function getCacheKey() {
        $key = parent::getCacheKey();
        $key .= $this->mHelper('config')->getHash();
        
        return md5($key);
	}
}

