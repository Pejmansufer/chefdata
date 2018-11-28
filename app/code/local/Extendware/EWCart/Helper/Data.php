<?php
class Extendware_EWCart_Helper_Data extends Extendware_EWCore_Helper_Data_Abstract
{
	
	public function getJs($type, array $options = array()) {
    	$js = 'try {  ';
    	switch ($type) {
    		case 'header_minicart':
    			$js .= "$$('div.header-minicart', 'div.header-cart').first().replace(" . json_encode($this->getHeaderMiniCartHtml()) . ");";
    			break;
    		case 'cart_sidebar':
    			$js .= "$$('div[class*=\"block-cart\"]', 'div.mini-cart').first().replace(" . json_encode($this->getCartSideBarHtml()) . ");";
    			break;
    		case 'top_cart_link':
    			$js .= "$$('a.top-link-cart').first().update(" . json_encode($this->getCartTopLinkHtml(Mage::getSingleton('checkout/cart')->getItemsQty())) . ");";
    			break;
    		case 'compare_sidebar':
    			$js .= "$$('div.block-compare', 'div.mini-compare').first().replace(" . json_encode($this->getCompareSideBarHtml()) . ");";
    			break;
    		case 'wishlist_sidebar':
    			$js .= Mage::getSingleton('core/layout')->createBlock('ewcart/dialog_wishlist_js_sidebar')->toHtml();
    			break;
    		case 'top_wishlist_link':
    			$js .= "$$('a.top-link-wishlist').first().update(" . json_encode($this->getWishlistTopLinkHtml(Mage::helper('wishlist')->getItemCount())) . ");";
    			break;
    		case 'wishlist':
    			$js .= "$$('div.my-wishlist').first().replace(" . json_encode(Mage::getSingleton('core/layout')->getBlock('wishlist')->toHtml()) . ");";
    			break;
    		case 'cart':
    			$js .= "$$('div.cart', 'div.layout-1column').first().update(" . json_encode(Mage::getSingleton('core/layout')->getBlock('cart')->toHtml()) . ");";
    			break;
    	}
    	$js .= ' } catch (e) {}';
    	return $js;
    }
    
	public function getCustomerWishlist() {
	    try {
            return Mage::getModel('wishlist/wishlist')->loadByCustomer(Mage::getSingleton('customer/session')->getCustomer(), true);
        }
        catch (Exception $e) {
           return false;
        }
	}
	
	public function isAddEnabledForProduct(Mage_Catalog_Model_Product $product) {
		$isSimpleProduct = (bool)in_array($product->getTypeId(), array(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE, Mage_Catalog_Model_Product_Type::TYPE_VIRTUAL));
		$hasOptions = (bool) $product->getTypeInstance(true)->hasOptions($product);

		$redirectToProductPage = false;
		if ($this->getConfig()->isCartOptionsEnabled() === false) {
			if ($hasOptions === true) {
				return false;
			}
		}
		
		if ($this->getConfig()->isCartAdvancedProductsEnabled() === false) {
			if ($isSimpleProduct === false) {
               return false;
			}
		}
		
		return true;
	}
	
    public function addProductToCart(Mage_Catalog_Model_Product $product, array $data) {
    	$isReadyForAdd = (bool)(isset($data['__ready']) && $data['__ready']); 
		$isSimpleProduct = (bool)in_array($product->getTypeId(), array(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE, Mage_Catalog_Model_Product_Type::TYPE_VIRTUAL));
		$hasOptions = (bool) $product->getTypeInstance(true)->hasOptions($product);

		if ($product->isSaleable() === false) {
			Mage::throwException($this->__('Product is currently not available for sale.'));
		}
		
		$redirectToProductPage = $this->isAddEnabledForProduct($product) ? false : true;
		if ($redirectToProductPage === false) {
			if ($isReadyForAdd === false && ($isSimpleProduct === false || $hasOptions === true)) {
				if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
	    			return false;
	    		} else if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_GROUPED) {
	    		    return false;
	    		} else if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE){
	    			return false;
	    		} else if ($hasOptions && $isSimpleProduct) {
					return false;
		    	}
			} else {
		    	$cart = Mage::getSingleton('checkout/cart');
				    $params = (array)$data;
				    $params['qty'] = @max($params['qty'], 1);
					$cart->addProduct($product, $params);
				$cart->save();
				
				Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
				Mage::dispatchEvent('checkout_cart_add_product_complete', array('product' => $product , 'request' => Mage::app()->getFrontController()->getAction()->getRequest(), 'response' => Mage::app()->getFrontController()->getAction()->getResponse()));
		        
				if (($wishlist = $this->getCustomerWishlist()) !== false) {
                    $items = $wishlist->getItemCollection();
                    foreach ($items as $item) {
                        if ($item->getProductId() == $product->getId()) {
                            $item->delete();
                        }
                    }
                }
				
                $block = Mage::getSingleton('core/layout')->createBlock('ewcart/dialog_cart_add_success');
                $block->addJavascript($this->getJs('top_cart_link'));
                $block->addJavascript($this->getJs('cart_sidebar'));
            	return $block;
			}
		}
		
		$block = Mage::getSingleton('core/layout')->createBlock('ewcart/dialog_cart_add_redirect');
		$block->setRedirectUrl($product->getProductUrl());
        return $block;
    }
    
    public function addProductToWishlist(Mage_Catalog_Model_Product $product, Mage_Wishlist_Model_Wishlist $wishlist) {
    	if (!$product->isVisibleInCatalog()) {
			Mage::throwException($this->__('Product must be visible in order to add it to wishlist.'));
		}
    	if (Mage::helper('wishlist')->isAllow() === false) {
			Mage::throwException($this->__('Wishlist is currently disabled.'));
		}
		$buyRequest = new Varien_Object();
		@$wishlist->addNewItem($product->getId(), $buyRequest);
		$wishlist->save();
		Mage::dispatchEvent('wishlist_add_product', array('wishlist' => $wishlist , 'product' => $product));
		Mage::helper('wishlist')->calculate();
		
		$block = Mage::getSingleton('core/layout')->createBlock('ewcart/dialog_wishlist_add_success');
		$block->addJavascript($this->getJs('wishlist_sidebar'));
		$block->addJavascript($this->getJs('top_wishlist_link'));
		return $block;
    }
    
	public function addProductToCompare(Mage_Catalog_Model_Product $product) {
		Mage::getSingleton('catalog/product_compare_list')->addProduct($product);
		Mage::dispatchEvent('catalog_product_compare_add_product', array('product'=>$product));
		Mage::helper('catalog/product_compare')->calculate();
		
		$block = Mage::getSingleton('core/layout')->createBlock('ewcart/dialog_compare_add_success');
		$block->addJavascript($this->getJs('compare_sidebar'));
		return $block;
    }
    
	public function getHeaderMiniCartHtml(){
		$block = @Mage::getSingleton('core/layout')->getBlock('minicart_head');
		if ($block) {
			$jsBlock = Mage::getSingleton('core/layout')->createBlock('core/template');
			$jsBlock->setTemplate('extendware/ewcart/minicart/js.phtml');
			$js = Mage::helper('core/js')->getScript($jsBlock->toHtml());
			return $block ? '<div class="header-minicart">' . $block->toHtml() . '</div>' . $js : '';
		}
		
		$block = @Mage::getSingleton('core/layout')->getBlock('headerCart');
		if ($block) return $block->toHtml();
		
		return '';
    }
    
    public function getCartSidebarHtml(){
		$block = Mage::getSingleton('core/layout')->createBlock('checkout/cart_sidebar');
		$block->addItemRender('simple','checkout/cart_item_renderer', 'checkout/cart/sidebar/default.phtml');
		$block->addItemRender('grouped','checkout/cart_item_renderer_grouped','checkout/cart/sidebar/default.phtml');
		$block->addItemRender('configurable','checkout/cart_item_renderer_configurable','checkout/cart/sidebar/default.phtml');
		$block->setTemplate('checkout/cart/sidebar.phtml');
		
		return $block->toHtml().Mage::helper('core/js')->getScript('truncateOptions();');
    }
    
	public function getCartTopLinkHtml($quantity){
		$block = Mage::getSingleton('core/layout')->createBlock('ewcart/cart_top_link');
		$block->setQuantity($quantity);
		return $block->toHtml();
    }
    
	public function getWishlistTopLinkHtml($quantity){
		$block = Mage::getSingleton('core/layout')->createBlock('ewcart/wishlist_top_link');
		$block->setQuantity($quantity);
		return $block->toHtml();
    }
    
    public function getWishlistSidebarHtml(){
		$block = Mage::getSingleton('core/layout')->createBlock('wishlist/customer_sidebar')->setTemplate('wishlist/sidebar.phtml');
		return  $block->renderView();
    }
    
    public function getCompareSidebarHtml(){
		$block = Mage::getSingleton('core/layout')->createBlock('core/template')->setTemplate('catalog/product/compare/sidebar.phtml');
		return $block->renderView();
    
    }
}
