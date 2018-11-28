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
class AdjustWare_Nav_Block_Rewrite_FrontCatalogCategoryView extends Mage_Catalog_Block_Category_View
{
    public function getProductListHtml()
    {
        $html = parent::getProductListHtml();
        if (parent::getCurrentCategory()->getIsAnchor()){
            $html = Mage::helper('adjnav')->wrapProducts($html);
        }
        return $html;
    }   

    public function getCmsBlockHtml()
    {
        if (parent::isContentMode())
        {
            return Mage::helper('adjnav')->wrapProducts(parent::getCmsBlockHtml());
        } 
        return parent::getCmsBlockHtml();
    }
    
    /**
     * Check if category display mode is "Static Block Only"
     * For anchor category with applied filter Static Block Only mode not allowed
     *
     * @return bool
     */
    public function isContentMode()
    {
        $res = parent::isContentMode();
        $category = $this->getCurrentCategory();
        $filters = Mage::helper('adjnav')->getParams();
        if ($res && $category->getIsAnchor() && sizeof($filters)>0) {
            $res = false;
        }
        return $res;
    }

     /**
     * Retrieve current category model object
     *
     * @return Mage_Catalog_Model_Category
     */
    public function getCurrentCategory()
    {
        $categoryId =(int)$this->getRequest()->getQuery('cat');
        if(!$categoryId)
        {
            return parent::getCurrentCategory();
        }
        else
        {
            return Mage::getModel('catalog/category')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($categoryId);
        }
    }
    
}