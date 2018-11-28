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
class AdjustWare_Nav_Model_Catalog_Layer_Filter_Category extends Mage_Catalog_Model_Layer_Filter_Category
{
    protected $cat = null;

    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        // very small optimization
        $catId = (int) Mage::helper('adjnav')->getParam($this->getRequestVar());
        if ($catId){
            $request->setParam($this->getRequestVar(), $catId);
            parent::apply($request, $filterBlock);
        }
        return $this;
    }

    public function getRootCategory()
    {
        if (is_null($this->cat)){
            $this->cat = Mage::getModel('catalog/category')
                ->load($this->getLayer()->getCurrentStore()->getRootCategoryId());
        }
        return $this->cat;
    }
    
    protected function _getItemsData()
    {
        $key = $this->getLayer()->getStateKey().'_SUBCATEGORIES';
        $key .= Mage::helper('adjnav')->getCacheKey('cat');
        $pageKey  = Mage::getBlockSingleton('page/html_pager')->getPageVarName();
        $queryStr =  Mage::helper('adjnav')->getParams(true, $pageKey);
        $data = $this->getLayer()->getAggregator()->getCacheData($key);

        if ($data === null) {
            $categories = array();
            $category = $this->getCategory();

            /** @var $categoty Mage_Catalog_Model_Categeory */
            if(Mage::helper('adjnav')->getCategoryDisplayType() !== 'default') {
                $categories = Mage::helper('adjnav')->getCategories($category->getId());
            }
            else {
                $categories = $category->getChildrenCategories();
            }

            $data = array();
            $level = 0;
            $parent = null; 
            
			if(Mage::helper('adjnav')->isHomePage() && Mage::helper('adjnav')->getCategoryDisplayType() == 'dropdown') {
                $data[] = array(
                    'label'       => 'Please Select',
                    'value'       => '',
                    'level'       => 0,
                    'category_id' => '',
                    'uri'   => ''
                );
            }
			
            if ($category->getLevel() > 1)
            { // current category is not root
                $parent = $category->getParentCategory();
                
                ++$level;
                if ($parent->getLevel()>1){
                    $data[] = array(
                        'label'       => $parent->getName(),
                        'value'       => $parent->getId(),
                        'level'       => $level,
                        'category_id' => $parent->getId(),
                        'uri'         => $parent->getUrl(),
                    );
                }
                //always include current category
                ++$level;
                $data[] = array(
                    'label'       => $category->getName(),
                    'value'       => '',
                    'level'       => $level,
                    'is_current'  => true,
                    'category_id' => $category->getId(),
                    'uri'         => $queryStr,
                );
            }

            $productCollection=$this->getLayer()->getProductCollection();
            Mage::helper('adjnav')->addManufacturerFilter($productCollection->getSelect());

            $productCollection->addCountToCategories($categories);

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

            if (Mage::getStoreConfig('design/adjnav/reset_filters'))
            {
                $queryStr = '';
            }
            
            for ($i=0, $n=sizeof($data); $i<$n; ++$i) {
                $url = $data[$i]['uri'];
                $pos = strpos($url, '?');
                if ($pos)
                    $url = substr($url, 0, $pos);
                $data[$i]['uri'] = $url . $queryStr;
            }

            $tags = $this->getLayer()->getStateTags();
            $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
        }

        return $data;
    }    
    
    protected function _initItems()
    {
        $data = $this->_getItemsData();
        $items=array();
        foreach ($data as $itemData) {
            $obj = new Varien_Object();
            $obj->setData($itemData);
            $obj->setUrl($itemData['value']);
            
            $items[] = $obj;
        }
        $this->_items = $items;
        return $this;
    }
    public function getFilterCategory(Zend_Controller_Request_Abstract $request)
    {
        $filter = (int) $request->getParam($this->getRequestVar());
        if (!$filter) {
            return $filter;
        }
        $this->_categoryId = $filter;
        
        $category = $this->getCategory();
        return $category;
    }

}