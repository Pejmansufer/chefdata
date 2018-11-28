<?php
class Extendware_EWCart_Block_Catalog_Product_View_Js extends Extendware_EWCore_Block_Mage_Core_Template
{
 	public function getProduct() {
 		return Mage::registry('product');
 	}
}