<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Categoryfilter
 * @copyright   Copyright (c) 2013 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)

 */

class Sashas_Categoryfilter_Helper_Data extends Mage_Core_Helper_Abstract
{	
	public function getCategoryOptions() {
		$option_array=array();
		$rootCategoryId = Mage::app()->getStore($storeId)->getRootCategoryId();
		$category_collection=Mage::getModel('catalog/category')->getCollection()->addNameToResult()->addAttributeToSort('position', 'asc');
		  
		foreach ($category_collection as $category) 
			if ($category->getId() >1 && $category->getName()!='Root Catalog')
				$option_array[$category->getId()]=$category->getName();
			 				 
		return $option_array;
	}
}
	  