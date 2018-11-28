<?php
abstract class Extendware_EWLayeredNav_Block_Dialog_Abstract extends Extendware_EWCore_Block_Mage_Core_Template
{
 	protected $javascript = array();

	public function getJavascript() {
 		return $this->javascript;
 	}
 	
 	public function addJavascript($data) {
 		$this->javascript[] = $data;
 		return $this;
 	}
}