<?php
abstract class Extendware_EWQuickView_Block_Dialog_Abstract extends Extendware_EWCore_Block_Mage_Core_Template
{
 	protected $javascript = array();
 	
 	public function getProduct() {
 		return Mage::registry('ew:product');
 	}
 	
	public function getJavascript() {
 		return $this->javascript;
 	}
 	
 	public function addJavascript($data) {
 		$this->javascript[] = $data;
 		return $this;
 	}
}