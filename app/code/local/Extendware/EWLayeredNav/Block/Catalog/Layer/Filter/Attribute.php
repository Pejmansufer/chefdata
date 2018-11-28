<?php
class Extendware_EWLayeredNav_Block_Catalog_Layer_Filter_Attribute extends Mage_Catalog_Block_Layer_Filter_Attribute
{
	protected $selectedItemsCount = null;
	protected $items = null;
	
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewlayerednav/catalog/layer/filter/attribute.phtml');
        $this->_filterModelName = 'ewlayerednav/catalog_layer_filter_attribute';
    }
    
	public function getItems()
    {
    	if ($this->items === null) {
    		$this->items = array();
	        $items = parent::getItems();
	        if (Mage::helper('ewlayerednav/config')->isAttributeOptionSortingEnabled() === false) {
	        	$this->items = $items;
	        } else {
	        	$itemGroups = array();
	        	foreach ($items as $item) {
	        		$index = (int)$this->isSelected($item);
	        		if (isset($itemGroups[$index]) === false) {
	        			$itemGroups[$index] = array();
	        		}
	        		
	        		$itemGroups[$index][] = $item;
	        	}
	        	ksort($itemGroups, SORT_NUMERIC);
	        	foreach ($itemGroups as $group) {
	        		$this->items = array_merge($group, $this->items);
	        	}
	        }
    	}
    	
    	return $this->items;
    }
    
	public function getRequestVar() {
    	return $this->_filter->getRequestVar();
    }
    
    public function getClearUrl($route = null)
    { 
    	if (!$route) $route = 'catalog/category/view';
        $url = Mage::getUrl($route, array(
            '_current'     => true, 
            '_use_rewrite' => true, 
            '_query'       => array($this->_filter->getRequestVar() => null),
         )); 
        
        return $url;
    }
    
    public function getHtmlId($item)
    {
        return $this->_filter->getRequestVar() . '-' . $item->getValueString();        
    }
    
    public function hasSelectedOptions() {
    	$activeState = $this->_filter->getActiveState();
    	return (empty($activeState) === false);
    }
    
    public function getMaxVisibleOptions() {
    	$maxVisible = Mage::helper('ewlayerednav/config')->getMaxVisibleAttributeOptions();
    	if (!$maxVisible) $maxVisible = 99999999;
    	return max($maxVisible, $this->getSelectedItemsCount());
    }
    
	public function getItemsCount()
    {
        return $this->_filter->getItemsCount();
    }
    
 	public function getSelectedItemsCount() {
 		if ($this->selectedItemsCount === null) {
 			$this->selectedItemsCount = 0;
	    	$items = $this->getItems();
	    	foreach ($items as $item) {
	    		if ($this->isSelected($item) === true) {
	    			$this->selectedItemsCount++;
	    		}
	    	}
 		}
    	return $this->selectedItemsCount;
    }
    
    public function isSelected($item)
    {
        $ids = (array)$this->_filter->getActiveState();
        return in_array($item->getValueString(), $ids);        
    }
    
    public function getItemUrl($item) {
    	return $this->urlEscape($item->getUrl());
    }
}