<?php
/*
* Copyright (c) 2015 www.magebuzz.com
*/
class Magebuzz_Flatrateperproduct_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function isHideIfZero() {
		$storeId = Mage::app()->getStore()->getId();
		return (bool) Mage::getStoreConfig('carriers/flatrateperproduct/hide_if_zero');
	}

}