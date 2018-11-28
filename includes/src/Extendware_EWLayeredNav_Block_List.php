<?php
class Extendware_EWLayeredNav_Block_List extends Mage_Core_Block_Template
{
    protected $_productCollection;

    public function getListBlock()
    {
        return $this->getChild('product_list');
    }

    public function setListOrders() {
    	if (Mage::helper('ewlayerednav')->isSearch() === false){
            return;
    	}
    	
        $category = Mage::getSingleton('catalog/layer')->getCurrentCategory();
        
        /* @var $category Mage_Catalog_Model_Category */
        $availableOrders = $category->getAvailableSortByOptions();
        unset($availableOrders['position']);
        $availableOrders = array_merge(array(
            'relevance' => $this->__('Relevance')
        ), $availableOrders);

        $this->getListBlock()->setAvailableOrders($availableOrders)
            ->setDefaultDirection('desc')
            ->setSortBy('relevance');

        return $this;
    }
    
    public function setListCollection() {
        $this->getListBlock()
           ->setCollection($this->_getProductCollection());
       return $this;
    }

    protected function _toHtml()
    {
        $this->setListOrders();
        $this->setListModes();
        $this->setListCollection();
        
        $html = $this->getChildHtml('product_list');
        $html = Mage::helper('ewlayerednav')->processProductListHtml($html);
        
        return $html;
    }

    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
	        if (Mage::helper('ewlayerednav')->isSearch()) $this->_productCollection = Mage::getSingleton('catalogsearch/layer')->getProductCollection();
	    	else $this->_productCollection = Mage::getSingleton('catalog/layer')->getProductCollection();
	        if (!Mage::registry('ew:current_product_collection')) {
	    		Mage::register('ew:current_product_collection', $this->_productCollection);
	    	}
        }

        return $this->_productCollection;
    }

}
