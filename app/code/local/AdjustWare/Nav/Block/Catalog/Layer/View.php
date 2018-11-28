<?php
/**
 * Layered Navigation Pro
 *
 * @category:    AdjustWare
 * @package:     AdjustWare_Nav
 * @version      2.5.11
 * @license:     HxObcXe62uQsQGs98TzFPLxwiM3aCakojlW7nJdmvI
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
class AdjustWare_Nav_Block_Catalog_Layer_View extends AdjustWare_Nav_Block_Rewrite_FrontCatalogLayerView
{
    protected $_filterBlocks = null;
	protected $_blocks = null;
    protected $_blockType;

    public function getStateInfo()
    {
        $hlp = Mage::helper('adjnav');

        $ajaxUrl = '';
        if ($hlp->isSearch()){
            $ajaxUrl = Mage::getUrl('adjnav/ajax/search');
        }
        elseif ($cat = $this->getLayer()->getCurrentCategory()){
            $ajaxUrl = Mage::getUrl('adjnav/ajax/category', array('id'=>$cat->getId(), '_secure' => Mage::app()->getFrontController()->getRequest()->isSecure()));
        }
        $ajaxUrl = $this->_stripQuery($ajaxUrl);

        //it could be search, home or category
        $url     = $hlp->getContinueShoppingUrl();

        $pageKey = Mage::getBlockSingleton('page/html_pager')->getPageVarName();
        $queryStr = $hlp->getParams(true, $pageKey);
        if ($queryStr)
            $queryStr = substr($queryStr,1);

        $this->setClearAllUrl($hlp->getClearAllUrl($url));

        if (false !== strpos($url, '?'))
        {
            $url = substr($url, 0, strpos($url, '?'));
        }
        return array($url, $queryStr, $ajaxUrl);
    }

    private function _stripQuery($url)
    {
        $pos = strpos($url, '?');
        if (false !== $pos)
            $url = substr($url, 0, $pos);

        return $url;
    }

    public function bNeedClearAll()
    {
        return Mage::helper('adjnav')->bNeedClearAll();
    }

	protected function _beforeToHtml()
    {
        if(!is_null($this->_blocks)) {
			foreach ($this->_blocks as $name => $block)
			{
				$block->init();
			}
		}
        $this->getLayer()->apply();
        $this->getLayer()->getProductCollection();
    }
	
    protected function _prepareLayout()
    {
		if ((string)Mage::getConfig()->getModuleConfig('Aitoc_Aitshopassist')->active == 'true' && Mage::app()->getRequest()->get('aitanswer') != '')
	        {
		    Mage::helper('aitshopassist')->applyAitanswerFilters($this->getLayer()->getProductCollection());
		}

    	// Notifies Magento Booster that the Layered Navigation is loaded
        Mage::register('adjustware_layered_navigation_view', true, true);

        // get current category ID

        $category = Mage::registry('current_category');

        if ($category)
        {
            $categoryId = (int) $this->getRequest()->getParam('cat', false);
            if($categoryId && ($categoryId != $category->getId())) 
            {
                 $iCurrentCatId = $categoryId;
            }
            else
            {
                $iCurrentCatId = $category->getId();
            }
        }
        else
        {
            $iCurrentCatId = null;
        }

        // get last cat ID

        $sessionObject = Mage::getSingleton('catalog/session');
        $request = Mage::app()->getRequest();

        if ($sessionObject && ($iLastCatId = $sessionObject->getAdjnavLastCategoryId()))
        {
            if (($iCurrentCatId != $iLastCatId)) //&& !$request->isXmlHttpRequest())
            {
                Mage::register('adjnav_new_category', true);
            }
        }

        $sessionObject->setAdjnavLastCategoryId($iCurrentCatId);

        //preload setting
        $this->setIsRemoveLinks(Mage::getStoreConfig('design/adjnav/remove_links'));

        //blocks
        $this->createCategoriesBlock();

        $filterableAttributes = $this->_getFilterableAttributes();

        // we rewrite this piece of code
        // to make sure price filter is applied last
        $blocks = array();
        foreach ($filterableAttributes as $attribute)
        {
            $blockType = 'adjnav/catalog_layer_filter_attribute';

            if ($attribute->getFrontendInput() == 'price')
            {
                $blockType = 'adjnav/catalog_layer_filter_price';
            }

            $name = $attribute->getAttributeCode() .'_filter';

            $blocks[$name] = $this->getLayout()->createBlock($blockType)
                ->setLayer($this->getLayer())
                ->setAttributeModel($attribute);

            $this->setChild($name, $blocks[$name]);
        }

        $this->_blocks = $blocks;
        return Mage_Core_Block_Template::_prepareLayout();
    }

    protected function createCategoriesBlock(){
	    $block = Mage::helper('adjnav')->isSearch() ? 'adjnav/catalog_layer_filter_categorysearch' : 'adjnav/catalog_layer_filter_category';
        if ('none' != Mage::helper('adjnav')->getCategoryFilterEnabled() && Mage::helper('adjnav')->getCategoryLayeredBlockType() == $this->_blockType)
        {
            $categoryBlock = $this->getLayout()->createBlock($block)
                ->setLayer($this->getLayer())
                ->init();
            $this->setChild('category_filter', $categoryBlock);
        }
    }

    public function getFilters()
    {
        if (is_null($this->_filterBlocks))
        {
            $filters = array();
            $categoryFilter = $this->_getCategoryFilter();
            if ($categoryFilter && Mage::helper('adjnav')->getCategoryLayeredBlockType() == $this->_blockType) {
                $filters[] = $categoryFilter;
            }

            $filterableAttributes = $this->_getFilterableAttributes();
            foreach ($filterableAttributes as $attribute) {
                if($attribute->getAdjnavBlockType() == $this->_blockType) {
                    $filters[] = $this->getChild($attribute->getAttributeCode() . '_filter');
                }
            }

            $this->_filterBlocks = $filters;

            /* @TODO Create Mage::dispatchEvent() here and create an observer in Visualize your attributes module */
            $val = Mage::getConfig()->getNode('modules/AdjustWare_Icon/active');
            if ((string)$val == 'true')
            {
                Mage::helper('adjicon')->addIconsToFilters($this->_filterBlocks);
            }

            $this->_rangeFilters();
            if(Mage::helper('adjnav')->getShopByBrandsStatus()) {
                Mage::dispatchEvent('aitoc_aitmanufacturers_layer_filters_get_after', array('layer_view_block' => $this));
            }
        }

        return $this->_filterBlocks;
    }

    protected function _rangeFilters()
    {
        $featuredLimit  = Mage::helper('adjnav/featured')->getFeaturedAttrsLimit();
        $featuredLimitDisabled = 0 == $featuredLimit;

        if (!$featuredLimit && !$featuredLimitDisabled)
        {
            return false;
        }

        $newFilterOrder = array();
        $attributes     = array();
        foreach ($this->_filterBlocks as $filter)
        {
            if ($filter instanceof AdjustWare_Nav_Block_Catalog_Layer_Filter_Attribute)
            {
                $attributes[$filter->getAttributeId()] = $filter;
            }
            else
            {
                $newFilterOrder[] = $filter;
            }
        }

        $attributes = Mage::getModel('adjnav/eav_entity_attribute_stat')->rangeAttributes($attributes);

        if (Mage::helper('adjnav/featured')->isRangeAttributes())
        {
            $this->_filterBlocks = array_merge($newFilterOrder, $attributes);
        }
    }

    protected function _getAttributesCount()
    {
        $count = 0;
        foreach ($this->_filterBlocks as $filter)
        {
            if ($filter instanceof AdjustWare_Nav_Block_Catalog_Layer_Filter_Attribute && $filter->getItemsCount())
            {
                $count++;
            }
        }

        return $count;
    }

    public function isShowMoreAttributesButton()
    {
        $featuredLimit = Mage::helper('adjnav/featured')->getFeaturedAttrsLimit();

        return ($featuredLimit && $featuredLimit < $this->_getAttributesCount());
    }
}