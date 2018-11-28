<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Categoryfilter
 * @copyright   Copyright (c) 2013 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)

 */

class Sashas_Categoryfilter_Block_Product_Grid extends Mage_Adminhtml_Block_Catalog_Product_Grid
{

    public  function _addCategoryFilter( $collection, $column) {
    	    	 
    	if (!$value = $column->getFilter()->getValue()) {
    		return;
    	}
    	$collection->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', '{{table}}.category_id='.$column->getFilter()->getValue(), 'inner');
    }
    
    protected function _prepareColumns()
    {    	  
    	$_storeId = Mage::app()->getStore()->getStoreId();
    	$extension_enabled=Mage::getStoreConfig('categoryfilter/categoryfilter_group/extension_enabled',$_storeId);
    	if ($extension_enabled) {
	        $this->addColumnAfter('category_id',
	            array(
	                'header'=> Mage::helper('categoryfilter')->__('Category'),
	                'width' => '150px',
	                'type'  => 'options',
	            	'filter_condition_callback' =>  array($this, '_addCategoryFilter'),
	            	'options' => Mage::helper('categoryfilter')->getCategoryOptions(),
	                'index' => 'category_ids',
	            	'renderer' => 'Sashas_Categoryfilter_Block_Product_Renderer_Categories'
	        ),'set_name');
    	}
        return parent::_prepareColumns();
    }
 
}
