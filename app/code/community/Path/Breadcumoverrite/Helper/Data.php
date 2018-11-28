<?php
class Path_Breadcumoverrite_Helper_Data extends Mage_Catalog_Helper_Data
{
	    public function getBreadcrumbPath()
    {
        if (!$this->_categoryPath) {

            $path = array();
            if ($category = $this->getCategory()) {
                $pathInStore = $category->getPathInStore();
                $pathIds = array_reverse(explode(',', $pathInStore));

                $categories = $category->getParentCategories();

                // add category path breadcrumb
                foreach ($pathIds as $categoryId) {
                    if (isset($categories[$categoryId]) && $categories[$categoryId]->getName()) {
                        $path['category'.$categoryId] = array(
                            'label' => $categories[$categoryId]->getName(),
                            'link' => $this->_isCategoryLink($categoryId) ? $categories[$categoryId]->getUrl() : ''
                        );
                    }
                }
            }
            if ($this->getProduct()) {
           $categoryIds=$this->getProduct()->getCategoryIds();
            	foreach($categoryIds as $categoryId)
            	{
            		$tempCollect=Mage::getModel('catalog/category')->load($categoryId)->getChildren();
            		if(!$tempCollect)
            		{
            			$parentCategory=$categoryId;
            		}
            	}
            	$path = array();
            	$parCategory = Mage::getModel('catalog/category')->load($parentCategory);
            	$pathInStore = $parCategory->getPathInStore();
            	$pathIds = array_reverse(explode(',', $pathInStore));
            	$categories = $parCategory->getParentCategories();
                // add category path breadcrumb
                foreach ($pathIds as $categoryId) {
                	if (isset($categories[$categoryId]) && $categories[$categoryId]->getName()) {
                		$path['category'.$categoryId] = array(
                			'label' => $categories[$categoryId]->getName(),
                			'link' => $this->_isCategoryLink($categoryId) ? $categories[$categoryId]->getUrl(): ''
                		);
                	}
                }
                $path['product'] = array('label'=>$this->getProduct()->getName());
            }
            $this->_categoryPath = $path;
        }
        return $this->_categoryPath;
    }
}