<?php

/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Categoryfilter
 * @copyright   Copyright (c) 2013 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)

 */

class Sashas_Categoryfilter_Block_Product_Renderer_Categories extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    
    public function render(Varien_Object $row)
    {
    	$product_categories=array();
    	$product_id=$row->getData('entity_id');
    	$_product=Mage::getModel('catalog/product')->load($product_id);
    	$categories = $_product->getCategoryCollection()->addAttributeToSelect('name');
    	foreach ($categories as $category) 
    		array_push($product_categories,$category->getName());
    		     
        return implode(', ',$product_categories);
    }
}
