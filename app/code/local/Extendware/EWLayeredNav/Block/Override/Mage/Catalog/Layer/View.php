<?php
class Extendware_EWLayeredNav_Block_Override_Mage_Catalog_Layer_View extends Extendware_EWLayeredNav_Block_Override_Mage_Catalog_Layer_View_Bridge
{
	protected $filters;
	
    public function isEnabled() {
    	return Mage::helper('ewlayerednav/config')->isBrowseEnabled();
    }
    
	public function getFilters()
    {
    	if ($this->filters === null) {
    		$this->filters = array();
    		
	    	$prependFilters = array();
	    	$categoryFilter = $this->_getCategoryFilter();
	        if ($categoryFilter) {
	            $prependFilters[] = $categoryFilter;
	        }
			
	        $filters = array();
	        $filterableAttributes = $this->_getFilterableAttributes();
	        foreach ($filterableAttributes as $attribute) {
	        	if ($attribute->getAttributeCode() == 'price' and Mage::helper('ewlayerednav/config')->isPriceFirstEnabled() === true) {
	        		$prependFilters[] = $this->getChild($attribute->getAttributeCode().'_filter');
	        	} else {
	            	$filters[] = $this->getChild($attribute->getAttributeCode().'_filter');
	        	}
	        }
			
	        if (Mage::helper('ewlayerednav/config')->isAttributeSortingEnabled() === false) {
	        	$this->filters = $filters;
	        } else {
	        	$filterGroups = array();
	        	foreach ($filters as $filter) {
	        		$index = (int)$filter->hasSelectedOptions();
	        		if (isset($filterGroups[$index]) === false) {
	        			$filterGroups[$index] = array();
	        		}
	        		
	        		$filterGroups[$index][] = $filter;
	        	}
	        	ksort($filterGroups, SORT_NUMERIC);
	        	foreach ($filterGroups as $group) {
	        		$this->filters = array_merge($group, $this->filters);
	        	}
	        	
	        }
	        
			foreach (array_reverse($prependFilters) as $filter) {
				array_unshift($this->filters, $filter);
			}
			
			Mage::register('ew:layerednav_filters', $this->filters);
    	}
    	
        return $this->filters;
    }
    
	public function getMaxVisibleFilters() {
    	$maxVisible = Mage::helper('ewlayerednav/config')->getMaxVisibleAttributes();
    	if (!$maxVisible) $maxVisible = 99999999;
    	if (Mage::helper('ewlayerednav/config')->isAttributeSortingEnabled() === false) return $maxVisible;
    	else return max($maxVisible, $this->getSelectedFiltersCount());
    }
    
	public function getSelectedFiltersCount() {
		$selectedFiltersCount = 0;
		$filters = $this->getFilters();
		foreach ($filters as $filter) {
			if ($filter->hasSelectedOptions() === true) {
				$selectedFiltersCount++;
			}
		}
    	return $selectedFiltersCount;
    }
    
    public function canShowFilter($filter) {
    	if (!$filter->getItemsCount()) return false;
    	
    	if ($filter instanceof Extendware_EWLayeredNav_Block_Catalog_Layer_Filter_Category) {
    		return true;
    	}
		
    	if (!$filter instanceof Extendware_EWLayeredNav_Block_Catalog_Layer_Filter_Price) {
	    	if ($filter->hasSelectedOptions() === false) {
	    		if ($filter->getItemsCount() < Mage::helper('ewlayerednav/config')->getMinAttributeOptions()) {
	    			return false;
	    		}
	    	}
    	}
    	
    	$items = $filter->getItems();
    	if (Mage::helper('ewlayerednav/config')->isEmptyFilterOptionsEnabled() === true) {
    		if ($filter->getItemsCount()) return true;
    	} else {
	    	foreach ($items as $item) {
				if ($item->getCount() > 0) {
					return true;
				}
	    	}
    	}
    	
    	return false;
    }
    
	public function getUrlForAjax($params = array(), $route = null)
    {
    	if (!$route) $route = 'ewlayerednav/navigation/browse';
    	
    	if (isset($params['id']) === false) {
    		$params['id'] = $this->getCategory()->getId();
    	}
    	
    	$url = $this->getUrl($route, $params);
    	if (strpos($url, '?') === false) $url .= '?';
    	return $url;
    }
    
    public function getCategory()
    {
    	return $this->getLayer()->getCurrentCategory();
    }
    
    protected function createCategoriesBlock(){
        if ('none' != Mage::helper('ewlayerednav/config')->getCategoryFilterType()){
            $categoryBlock = $this->getLayout()->createBlock('ewlayerednav/catalog_layer_filter_category')
                ->setLayer($this->getLayer())
                ->init();
            $this->setChild('category_filter', $categoryBlock);
        }
        
        return $this;
    }
    
    public function getClearUrl()
    {
		$url = null;	
		if ($this->getCategory()) {
			$url = $this->getCategory()->getUrl();
		}
    	
    	return $url;
    }
    
    public function getSearchParamString(array $include = array(), array $exclude = array()) {
    	return $this->getParamString($include, $exclude);
    }
    
    public function getParamString(array $include = array(), array $exclude = array())
    {
    	$exclude += array('ewlayerednav_current_url');
    	
		$queryStr = '';
		$query   = Mage::app()->getRequest()->getQuery();
		$query[Mage::getBlockSingleton('page/html_pager')->getPageVarName()] = null;
		foreach ($query as $k => $v) {
			if (empty($include) or in_array($k, $include) === true) {
				if (in_array($k, $exclude) === false) {
					if (strlen($v) > 0) $queryStr .= $k . '=' . urlencode($v) . '&';
				}
			}
		}
		$queryStr = trim($queryStr, '&');
		return $queryStr;
    }
    
	public function hasSelectedOptions() {
		$filters = $this->getFilters();
		foreach ($filters as $filter) {
			if ($filter->hasSelectedOptions() === true) {
				return true;
			}
		}
    	
		return false;
    }

	protected function _getAttributeFilterBlockName()
    {
        return 'ewlayerednav/catalog_layer_filter_attribute';
    }
    
	protected function _getAttributeFilterBlockNameFor($type)
    {
        return 'ewlayerednav/catalog_layer_filter_' . $type;
    }
    
	protected function _prepareLayout()
    {
    	if (!$this->isEnabled()) {
    		return parent::_prepareLayout();
    	}
    	
        $this->createCategoriesBlock();
        
        $blocks = array();
        $filterableAttributes = $this->_getFilterableAttributes();
        foreach ($filterableAttributes as $attribute) {
        	$blockType = $this->_getAttributeFilterBlockName();
        	if ($attribute->getFrontendInput() == 'price') {
                $blockType = $this->_getAttributeFilterBlockNameFor('price');
            }

            $name = $attribute->getAttributeCode().'_filter';
            $blocks[$name] = $this->getLayout()->createBlock($blockType)
                ->setLayer($this->getLayer())
                ->setAttributeModel($attribute);
			
            $this->setChild($name, $blocks[$name]);
        }

        foreach ($blocks as $name=>$block){
            if ($name != 'price_filter') {
                $block->init();
            }
        }
        
        if (!empty($blocks['price_filter'])) {
            $blocks['price_filter']->init();
        }
        
        $this->getLayer()->apply();
        
        return Mage_Core_Block_Template::_prepareLayout();
    }
}