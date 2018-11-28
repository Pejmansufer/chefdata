<?php
if (defined('COMPILER_INCLUDE_PATH')) {
	require_once('Extendware_EWQuickView_Controller_Ajax_Action.php');
} else {
	require_once('Extendware/EWQuickView/Controller/Ajax/Action.php');
}
?><?php
class Extendware_EWQuickView_ProductController extends Extendware_EWQuickView_Controller_Ajax_Action
{
	protected function _initProduct() 
	{
		$model = Mage::registry('ew:product');
		if ($model === null) {
			try {
				$productId = $this->getRequest()->getParam('product', $this->getRequest()->getParam('id'));
				$model = Mage::getModel('catalog/product')->setStoreId(Mage::app()->getStore()->getId());
				$model->load($productId);
				if (!$model->getId() > 0) {
					return false;
				}
			} catch (Exception $e) {
				return false;
			}
			
			Mage::register('ew:product', $model);
			Mage::register('ew:current_product', $model);
			Mage::register('product', $model);
			Mage::register('current_product', $model);
		}
		
  		return $model;
	}
	
	protected function _initProductLayout($product)
    {
    	$update = $this->getLayout()->getUpdate();
        $update->addHandle('default');
        $this->addActionLayoutHandles();

        $themeParts = explode('_', $this->mHelper('config')->getTheme());
        $key = $themeParts[0] . '_' . $product->getTypeId();

        $update->addHandle('EWQUICKVIEW_PRODUCT_TYPE_'.$key);
        $update->addHandle('EWQUICKVIEW_PRODUCT_'.$key);

         if ($this->mHelper('config')->isLoadingProductLayoutsEnabled()) {
            if ($product->getPageLayout()) $this->getLayout()->helper('page/layout')->applyHandle($product->getPageLayout());
			if (class_exists('Mage_Catalog_Helper_Product_View') === true) {
            	Mage::helper('catalog/product_view')->initProductLayout($product, $this);
			}
        }

        $this->loadLayoutUpdates();
        $update->addUpdate($product->getCustomLayoutUpdate());
        
        $this->generateLayoutXml()->generateLayoutBlocks();
        return $this;
    }
    
	protected function _initSendToFriendModel()
    {
        $model  = Mage::getModel('sendfriend/sendfriend');
        $model->setRemoteAddr(Mage::helper('core/http')->getRemoteAddr(true));
        $model->setCookie(Mage::app()->getCookie());
        $model->setWebsiteId(Mage::app()->getStore()->getWebsiteId());

        Mage::register('send_to_friend_model', $model);

        return $model;
    }
    
    public function viewAction()
    {
    	$this->_initSendToFriendModel();
    	$product = $this->_initProduct();
    	if (!$product or !$product->getId()) return;
		
    	if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
    		if ($this->mHelper('config')->isBundleSupportEnabled() === false) {
    			$errorMessages = array($this->__('Quick view is not possible for this product. Please visit product page.'));
    			$block = Mage::getSingleton('core/layout')->createBlock('ewquickview/dialog_product_view_error');
				$block->setMessages($errorMessages);
    			return $this->getResponse()->setBody($block->toHtml());
    		}
    	}
    	
        $this->_initProductLayout($product);
			$this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('tag/session');
            $this->_initLayoutMessages('checkout/session');
            
            $block = $this->getLayout()->getBlock('product.info');
           	if ($block) $block->setRefererUrl($this->_getRefererUrl());
           	
        $this->renderLayout();
        return $this;
    }
    
    public function galleryAction() {
    	return $this->_redirect('catalog/*/*', array('_current' => true));
    }
}