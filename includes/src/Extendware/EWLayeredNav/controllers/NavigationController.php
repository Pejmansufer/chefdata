<?php
if (defined('COMPILER_INCLUDE_PATH')) {
	require_once('Extendware_EWLayeredNav_Controller_Ajax_Action.php');
} else {
	require_once('Extendware/EWLayeredNav/Controller/Ajax/Action.php');
}
?><?php
class Extendware_EWLayeredNav_NavigationController extends Extendware_EWLayeredNav_Controller_Ajax_Action
{
    public function browseAction()
    {
        // init category
        $categoryId = (int) $this->getRequest()->getParam('id', false);
        if (!$categoryId) return;
		
		$brandUrlKey = $this->getRequest()->getParam('ewlayerednav_current_url');
		$brandUrlKey = explode('/',$brandUrlKey);
		$brand = Mage::getModel('shopbybrand/brand')->getCollection()
								->addFieldToFilter('url_key',$brandUrlKey)
								->getFirstItem()
								->getOptionId();
		if($brand)
			$this->getRequest()->setParam('manufacturer', $brand);
		
		
        $category = Mage::getModel('catalog/category')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($categoryId);
        Mage::register('current_category', $category);
        Mage::register('category', $category);         
        
        
        $this->loadLayout();
        $block = Mage::getSingleton('core/layout')->createBlock('ewlayerednav/dialog_blank');
        $block->addJavascript($this->mHelper()->getJs('navigation'));
	    $block->addJavascript($this->mHelper()->getJs('products'));
        
        return $this->getResponse()->setBody($block->toHtml());
    }
    
	public function searchAction()
    {
        $this->loadLayout();
        $block = Mage::getSingleton('core/layout')->createBlock('ewlayerednav/dialog_blank');
        $block->addJavascript($this->mHelper()->getJs('navigation'));
	    $block->addJavascript($this->mHelper()->getJs('products'));
        
        return $this->getResponse()->setBody($block->toHtml());
    }
    
}