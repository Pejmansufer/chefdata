<?php
class Extendware_EWLayeredNav_Block_Override_Mage_CatalogSearch_Layer extends Extendware_EWLayeredNav_Block_Override_Mage_CatalogSearch_Layer_Bridge
{

	public function isEnabled() {
    	return Mage::helper('ewlayerednav/config')->isSearchEnabled();
    }
    
    public function getLayer()
    {
        return Mage::getSingleton('catalogsearch/layer');
    }

    public function canShowBlock()
    {
        $availableResCount = (int) Mage::getStoreConfig(Mage_CatalogSearch_Model_Layer::XML_PATH_DISPLAY_LAYER_COUNT);
        if (!$availableResCount || ($availableResCount>=$this->getLayer()->getProductCollection()->getSize())) {
            return parent::canShowBlock();
        }
        return false;
    }
    
    protected function createCategoriesBlock(){
    	if ('none' != Mage::helper('ewlayerednav/config')->getCategoryFilterType()){
	        $categoryBlock = $this->getLayout()
	            ->createBlock('ewlayerednav/catalogsearch_layer_filter_category')
	            ->setLayer($this->getLayer())
	            ->init();
	        $this->setChild('category_filter', $categoryBlock);
    	}
        return $this;
    }
	
	public function getUrlForAjax($params=array(), $route = null)
    {
    	if (!$route) $route = 'ewlayerednav/navigation/search';
    	//$params['_query'] = array('q' => $this->getRequest()->getParam('q'));
    	return parent::getUrlForAjax($params, $route);
    }
    
	public function getClearUrl($route = null)
    {
    	return Mage::getUrl('catalogsearch/result/index', array('q'=>$this->getRequest()->getParam('q')));;
    }
    
	protected function _getAttributeFilterBlockName()
    {
        return 'ewlayerednav/catalogsearch_layer_filter_attribute';
    }
    
	protected function _getAttributeFilterBlockNameFor($type)
    {
        return 'ewlayerednav/catalogsearch_layer_filter_' . $type;
    }
}