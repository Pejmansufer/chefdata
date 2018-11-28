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
class AdjustWare_Nav_Block_Rewrite_FrontCatalogProductListToolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
    
    protected function _beforeToHtml()
    {
    	$this->getRequest()->setParam('cat', '');
    	
        if(Mage::helper('adjnav')->isPageAutoload())
        {
            $this->setTemplate('adjnav/product_list_toolbar.phtml');
        }
        return parent::_beforeToHtml();
    }
    
    public function getLimit()
    {
        if (Mage::helper('adjnav')->isPageAutoload())
        {
            $mode = $this->getCurrentMode();
            $limit = Mage::getStoreConfig('design/adjnav_endless_page/products_on_' . $mode . '_page');
            if ($limit)
            {
                $this->setData('_current_limit', $limit);
                return $limit;
            }
        }
        return parent::getLimit();
    }   
}
?>