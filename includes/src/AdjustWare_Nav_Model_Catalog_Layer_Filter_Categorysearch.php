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
class AdjustWare_Nav_Model_Catalog_Layer_Filter_Categorysearch extends Mage_Catalog_Model_Layer_Filter_Category
{
    protected function _getItemsData()
    {
        if (!isset($queryStr))
		{
			$queryStr = '';
		}
		$key = $this->getLayer()->getStateKey().'_SEARCH_SUBCATEGORIES';
        $key .= Mage::helper('adjnav')->getCacheKey('cat');
        $data = $this->getLayer()->getAggregator()->getCacheData($key);

        if ($data === null) {
            $category   = $this->getCategory();
            $categories = array();
            
            /** @var $category Mage_Catalog_Model_Categeory */
            if(Mage::helper('adjnav')->getCategoryDisplayType() !== 'default') {
                $categories = Mage::helper('adjnav')->getCategories($category->getId());
            }
            else {
                $categories = $category->getChildrenCategories();
            }

            $data = array();
            $level = 0;

			if(Mage::helper('adjnav')->getCategoryDisplayType() == 'dropdown') {
                $data[] = array(
                    'label'       => 'Please Select',
                    'value'       => '',
                    'level'       => 0,
                    'category_id' => '',
                    'uri'         => '',
                );
            }
			
            if ($category->getLevel() > 1){ // current category is not root
                $parent = $category->getParentCategory();
                
                ++$level;
                if ($parent->getLevel()>1){
                    $data[] = array(
                        'label'       => $parent->getName(),
                        'value'       => $parent->getId(),
                        'category_id' => $parent->getId(),
                        'count'       => 0,
                        'level'       => $level,
                        'uri'         => $parent->getUrl(),
                    );

                }
                //always include current category
                ++$level;
                $data[] = array(
                    'label'      => $category->getName(),
                    'value'      => '',
                    'level'      => $level,
                    'is_current' => true,
                    'uri'        => $queryStr,
                );
            }
             
            $this->getLayer()->getProductCollection()
                ->addCountToCategories($categories);

            ++$level;
            foreach ($categories as $cat) {
                if ($cat->getIsActive() && $cat->getProductCount()) {
                    if (Mage::helper('adjnav')->getCategoryDisplayType() !== 'default') {
                        $data[] = array(
                            'label'       => $cat->getName(),
                            'value'       => $cat->getId(),
                            'count'       => $cat->getProductCount(),
                            'level'       => $cat->getLevel(),
                            'category_id' => $cat->getId(),
                            'uri'         => $cat->getUrl(),
                        );
                    }
                    else {
                        $data[] = array(
                            'label'       => $cat->getName(),
                            'value'       => $cat->getId(),
                            'count'       => $cat->getProductCount(),
                            'level'       => $level,
                            'category_id' => $cat->getId(),
                            'uri'         => $cat->getUrl(),
                        );
                    }
                }
            }
            $tags = $this->getLayer()->getStateTags();
            $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
        }
    if (Mage::getStoreConfig('design/adjnav/reset_filters'))
    {
        $queryStr = '';
    }
    $pageKey  = Mage::getBlockSingleton('page/html_pager')->getPageVarName();
    $queryStr =  Mage::helper('adjnav')->getParams(true, $pageKey);            
            for ($i=0, $n=sizeof($data); $i<$n; ++$i) {
                $url = $data[$i]['uri'];
                $pos = strpos($url, '?');
                if ($pos)
                    $url = substr($url, 0, $pos);
                $data[$i]['uri'] = $url . $queryStr;
            }
        return $data;
    }

    protected function _initItems()
    {
        $data  = $this->_getItemsData();
        $items = array();
        foreach ($data as $itemData) {
            $obj = Mage::getModel('catalog/layer_filter_item');
            $obj->setData($itemData);
            $obj->setFilter($this);
            
            $items[] = $obj;
        }
        $this->_items = $items;
        return $this;
    }    
}