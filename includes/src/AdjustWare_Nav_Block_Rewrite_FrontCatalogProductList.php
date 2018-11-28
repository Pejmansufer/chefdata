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
class AdjustWare_Nav_Block_Rewrite_FrontCatalogProductList extends Mage_Catalog_Block_Product_List
{
     public function __construct(){
        parent::__construct();
        if(Mage::helper('adjnav')->isModuleEnabled('Aitoc_Aitproductslists'))
        {
              $this->setTemplate('aitcommonfiles/design--frontend--base--default--template--catalog--product--list.phtml');
        }
        else
        {
              $this->setTemplate('catalog/product/list.phtml');
        }
    }

    /**
     * @param integer $columnCount
     */
    public function setDefaultColumnCount($columnCount){
        $this->_defaultColumnCount=(int)$columnCount;
    }

    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getLoadedProductCollection()
    {
        if(Mage::getConfig()->getNode('modules/AdjustWare_Icon/active')
            && $this->_nameInLayout == 'product.list.icons'
            && $search = Mage::app()->getLayout()->getBlock('search.result'))
        {
            $this->setCollection($search->getProductCollection());
        }
        return $this->_getProductCollection();
    }
}