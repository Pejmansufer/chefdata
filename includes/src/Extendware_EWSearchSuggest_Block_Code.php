<?php

class Extendware_EWSearchSuggest_Block_Code extends Extendware_EWCore_Block_Mage_Core_Template
{
    public function _construct()
    {
    	parent::_construct();
        $this->setTemplate('extendware/ewsearchsuggest/code.phtml');
    }
    
    public function _toHtml() 
    {
    	if ($this->mHelper('config')->isEnabled()) {
    		return parent::_toHtml();
    	}
    	
    	return '';
    }
}
