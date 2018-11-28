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
// wrapper for product list on home page
class AdjustWare_Nav_Block_Homepage extends AdjustWare_Nav_Block_List
{
    protected function _prepareLayout()
    {
        $staticBlock = $this->getLayout()
            ->createBlock('cms/block', 'adj_nav_homepage')
            ->setBlockId('adj_nav_homepage');
        if ($staticBlock){
            $this->insert($staticBlock);
        }
        
        $productsBlock = $this->getLayout()
            ->createBlock('catalog/product_list', 'product_list')
            //->setColumnsCount(4)  
            ->setTemplate('catalog/product/list.phtml');
        //@todo  check gift registry compatibility     
        if ($productsBlock)
            $this->insert($productsBlock);

        return parent::_prepareLayout();
    } 
    
    protected function _toHtml()
    {
        $hlp = Mage::helper('adjnav');
        
        $html = $this->getChildHtml('adj_nav_homepage');
        if ($html && !$hlp->getParams()){
            $html = $hlp->wrapHomepage($html);
        }
        else{
            $html = parent::_toHtml();
        }
        
        return $html;
    }    
    
}