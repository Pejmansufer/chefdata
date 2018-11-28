<?php
/**
 * Magmodules.eu - http://www.magmodules.eu - info@magmodules.eu
 * =============================================================
 * NOTICE OF LICENSE [Single domain license]
 * This source file is subject to the EULA that is
 * available through the world-wide-web at:
 * http://www.magmodules.eu/license-agreement/
 * =============================================================
 * @category    Magmodules
 * @package     Magmodules_Reviewemail
 * @author      Magmodules <info@magmodules.eu>
 * @copyright   Copyright (c) 2015 (http://www.magmodules.eu)
 * @license     http://www.magmodules.eu/license-agreement/  
 * =============================================================
 */
 
class Magmodules_Reviewemail_Model_Reviewemail extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('reviewemail/reviewemail');
    }
  
  	public function sendReviewemail($reviewemailId, $orderid = '', $email_id = '') {  		
		
		$translate  = Mage::getSingleton('core/translate');
	 	$oldStore = Mage::app()->getStore();
		
		// Check if test email need to be sent (!$reviewemailId => Test email based on $orderid)
		if(!$reviewemailId) {
			$model  = Mage::getModel('reviewemail/reviewemail');
			$order = Mage::getModel("sales/order")->loadByIncrementId($orderid);
			$storeId = $order->getStoreId(); 
			$set = Mage::app()->setCurrentStore($storeId);				
			$customerEmail = Mage::getStoreConfig('reviewemail/testing/test_email', $storeId);
			$customerName = $order->getCustomerName();
		} else {
			$model  = Mage::getModel('reviewemail/reviewemail')->load($reviewemailId);
			$order = Mage::getModel("sales/order")->load($model->getOrderId());
			$email_id = $model->getEmailId();
			$storeId = $order->getStoreId(); 
			$set = Mage::app()->setCurrentStore($storeId);				
			$customerEmail = $order->getCustomerEmail();
			$customerName = $order->getCustomerName();
		}		

		$store = Mage::app()->getStore($storeId); 
		$url = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);		

		$sender =  Mage::getStoreConfig('reviewemail/config/reviewemail_sender', $storeId);
		$store_name =  Mage::getStoreConfig('general/store_information/name', $storeId); 
		$bcc_email = Mage::getStoreConfig('reviewemail/config/reviewemail_bccemail', $storeId); 
		$bcc_enabled = Mage::getStoreConfig('reviewemail/config/reviewemail_bccenabled', $storeId); 		
		$reviewemail_shopreview_type = Mage::getStoreConfig('reviewemail/email_config/reviewemail_shopreview_type', $storeId);
		$products = $this->getOrderProducts($order);

		// ==========================================
		// ============= Welcome block ============= 

		$welcome_block = ''; 
		$welcome_block_content = '';	

		if($email_id == '1') {
			$display = 	Mage::getStoreConfig('reviewemail/first_email/first_welcome_block', $storeId); 	
			if($display == '1') {
				$welcome_block = '1';
				if(Mage::getStoreConfig('reviewemail/first_email/first_welcome_block_static', $storeId)) {
					$static_block = Mage::getModel('cms/block')->setStoreId($storeId)->load(Mage::getStoreConfig('reviewemail/first_email/first_welcome_block_static', $storeId));	
					if($static_block->getIsActive()) { 
						$welcome_block_content = $static_block->getContent(); 
					}
				}
			}
			if($display == '2') {
				$welcome_block = '1';
				$welcome_block_content = nl2br(Mage::getStoreConfig('reviewemail/first_email/first_welcome_block_text', $storeId)); 	
			}
		} else {
			$display = 	Mage::getStoreConfig('reviewemail/second_email/second_welcome_block', $storeId); 	
			if($display == '1') {
				$welcome_block = '1';
				if(Mage::getStoreConfig('reviewemail/second_email/second_welcome_block_static', $storeId)) {
					$static_block = Mage::getModel('cms/block')->setStoreId($storeId)->load(Mage::getStoreConfig('reviewemail/second_email/second_welcome_block_static', $storeId));	
					if($static_block->getIsActive()) { 
						$welcome_block_content = $static_block->getContent(); 
					}
				}			
			}
			if($display == '2') {
				$welcome_block = '1';
				$welcome_block_content = nl2br(Mage::getStoreConfig('reviewemail/second_email/second_welcome_block_text', $storeId)); 	
			}
		}

		// ========================================
		// ============= Review block ============= 	
		
		$review_block = '';
		$review_block_title = '';
				
		if($email_id == '1') {	
			if(Mage::getStoreConfig('reviewemail/first_email/first_review_block', $storeId) > 0) {
				// check if there are visible products
				$visible = 0; $i = '';
				foreach($order->getAllItems() as $_item) {
					if($_item->getParentItem()) continue; else $i++; 
						$_product = Mage::getModel('catalog/product')->load($_item->getProductId());
						if($_product->getStatus() == '1') $visible++;
				}
				if($visible > 0) $review_block = '1';				
				$review_block_title = Mage::getStoreConfig('reviewemail/first_email/first_review_title', $storeId);
			}
		} else {
			if(Mage::getStoreConfig('reviewemail/second_email/second_review_block', $storeId) > 0) {
				// check if there are visible products
				$visible = 0; $i = '';
				foreach($order->getAllItems() as $_item) {
					if($_item->getParentItem()) continue; else $i++; 
						$_product = Mage::getModel('catalog/product')->load($_item->getProductId());
						if($_product->getStatus() == '1') $visible++;
				}			
				if($visible > 0) $review_block = '1';	
				$review_block_title = Mage::getStoreConfig('reviewemail/second_email/second_review_title', $storeId);				
			}
		}

		// ============================================
		// ============= Shopreview block ============= 
		
		$shopreview_block = ''; 
		$shopreview_block_content = '';	
		$shopreview_block_title = '';			
		
		if($email_id == '1') {
			$display = 	Mage::getStoreConfig('reviewemail/first_email/first_shopreview_block', $storeId); 	
			$shopreview_block_title = Mage::getStoreConfig('reviewemail/first_email/first_shopreview_title', $storeId); 
			if($display == '1') {
				$shopreview_block = '1';
				
				if(Mage::getStoreConfig('reviewemail/first_email/first_shopreview_block_static', $storeId)) {
					$static_block = Mage::getModel('cms/block')->setStoreId($storeId)->load(Mage::getStoreConfig('reviewemail/first_email/first_shopreview_block_static', $storeId));	
					if($static_block->getIsActive()) { 
						$shopreview_block_content = $static_block->getContent(); 
					}
				}
			}
			if($display == '2') {
				$shopreview_block = '1';
				$shopreview_block_content = nl2br(Mage::getStoreConfig('reviewemail/first_email/first_shopreview_block_text', $storeId)); 	
			}
		} else {
			$display = 	Mage::getStoreConfig('reviewemail/second_email/second_shopreview_block', $storeId); 	
			$shopreview_block_title = Mage::getStoreConfig('reviewemail/second_email/second_shopreview_title', $storeId); 
			if($display == '1') {
				$shopreview_block = '1';
				if(Mage::getStoreConfig('reviewemail/second_email/second_shopreview_block_static', $storeId)) {
					$static_block = Mage::getModel('cms/block')->setStoreId($storeId)->load(Mage::getStoreConfig('reviewemail/second_email/second_shopreview_block_static', $storeId));	
					if($static_block->getIsActive()) { 
						$shopreview_block_content = $static_block->getContent(); 
					}
				}
			}	
			if($display == '2') {
				$shopreview_block = '1';
				$shopreview_block_content = nl2br(Mage::getStoreConfig('reviewemail/second_email/second_shopreview_block_text', $storeId)); 
			}
		}		

		// ============================================
		// ============= Specification block ============= 
		
		$specs_block = ''; 
		$specs_block_content = '';	
		$specs_block_title = '';			
		
		if($email_id == '1') {
			$display = 	Mage::getStoreConfig('reviewemail/first_email/first_specs_block', $storeId); 	
			$specs_block_title = Mage::getStoreConfig('reviewemail/first_email/first_specs_title', $storeId); 
			if($display) {
				if($attribute = Mage::getStoreConfig('reviewemail/first_email/first_specs_attribute', $storeId)) {
					$items = Mage::helper('reviewemail')->getAllReviewProducts($order);
					$display_type = Mage::getStoreConfig('reviewemail/first_email/first_specs_display', $storeId);
					if(($display_type == 'hide') && (count($items) > 1)) {
						$shopreview_block_content = '';
					} else {						
						$i = 0;
						foreach($items as $_item) {
							$_product = Mage::getModel('catalog/product')->setStoreId($storeId)->load($_item->getProductId());	
							if($content = $_product->getData($attribute)) {
								$specs_block_content .= $_product->getData($attribute);
								if($i > 0) {
									$specs_block_content .= '<br/><br/>';								
								}
								$i++;
							}
						}
					}
				}
			}
		} else {
			$display = 	Mage::getStoreConfig('reviewemail/second_email/second_specs_block', $storeId); 	
			$specs_block_title = Mage::getStoreConfig('reviewemail/second_email/second_specs_title', $storeId); 
			if($display) {
				if($attribute = Mage::getStoreConfig('reviewemail/second_email/second_specs_attribute', $storeId)) {
					$items = Mage::helper('reviewemail')->getAllReviewProducts($order);
					$display_type = Mage::getStoreConfig('reviewemail/second_email/second_specs_display', $storeId);
					if(($display_type == 'hide') && (count($items) > 1)) {
						$static_block = '';
					} else {						
						$i = 0;
						foreach($items as $_item) {
							$_product = Mage::getModel('catalog/product')->setStoreId($storeId)->load($_item->getProductId());	
							if($content = $_product->getData($attribute)) {
								$specs_block_content .= $_product->getData($attribute);
								if($i > 0) {
									$specs_block_content .= '<br/><br/>';								
								}
								$i++;
							}
						}
					}
				}
			}	
		}
							
		// ============================================
		// ============= Cross sell block ============= 	
		
		$crosssel_block = '';
		$crosssel_block_title = '';
		
		if($email_id == '1') {		

			if(Mage::getStoreConfig('reviewemail/first_email/first_crosssel_block', $storeId) > 0) {
				
				$items 	= array(); 
				$cross_items	= array();
		
				foreach($order->getAllItems() as $_item) {	
					$items[] = array('id' => $_item->getProductId(), 'price' => $_item->getPrice());
				}

				krsort($items);
		
				foreach ($items as $item) {
					$product = Mage::getModel('catalog/product')->load($item['id']);
					$crosssell_products = $product->getCrossSellProducts(); 
					foreach($crosssell_products as $cross) {
						$cross_product = Mage::getModel('catalog/product')->load($cross->getId());				
						if($cross_product->getStatus() == '1') {
							$cross_items[] = array('id' => $cross->getId());				
						}
					}
				}
		
				if(count($cross_items) > 0) {
					$crosssel_block = '1';
					$crosssel_block_title = Mage::getStoreConfig('reviewemail/first_email/first_crosssel_title', $storeId);
				}
			}
		} else {
			if(Mage::getStoreConfig('reviewemail/second_email/second_crosssel_block', $storeId) > 0) {
				$items 	= array(); $cross_items	= array();
		
				foreach($order->getAllItems() as $_item) {
					$items[] = array('id' => $_item->getProductId(), 'price' => $_item->getPrice());
				}
				
				krsort($items);
		
				foreach ($items as $item) {
					$product = Mage::getModel('catalog/product')->load($item['id']);
					$crosssell_products = $product->getCrossSellProducts(); 
					foreach($crosssell_products as $cross) {
						$cross_product = Mage::getModel('catalog/product')->load($cross->getId());				
						if($cross_product->getStatus() == '1') {
							$cross_items[] = array('id' => $cross->getId());				
						}
					}
				}
		
				if(count($cross_items) > 0) {
					$crosssel_block = '1';
					$crosssel_block_title = Mage::getStoreConfig('reviewemail/second_email/second_crosssel_title', $storeId);					
				}
			}
		}

		// =========================================
		// ============= Up sell block ============= 	
		
		$upsell_block = '';
		$upsell_block_title = '';
		
		if($email_id == '1') {		
			if(Mage::getStoreConfig('reviewemail/first_email/first_upsell_block', $storeId) > 0) {
				
				$items = array(); 
				$upsell_items = array();
		
				foreach($order->getAllItems() as $_item) {
					$items[] = array('id' => $_item->getProductId(), 'price' => $_item->getPrice());
				}
				
				krsort($items);
		
				foreach ($items as $item) {
					$product = Mage::getModel('catalog/product')->load($item['id']);
					$upsell_products = $product->getUpsellProducts(); 
					foreach($upsell_products as $upsell) {
						$upsell_product = Mage::getModel('catalog/product')->load($upsell->getId());				
						if($upsell_product->getStatus() == '1') {
							$upsell_items[] = array('id' => $upsell->getId());				
						}
					}
				}

				if(count($upsell_items) > 0) {
					$upsell_block = '1';
					$upsell_block_title = Mage::getStoreConfig('reviewemail/first_email/first_upsell_title', $storeId);
				}
			}
		} else {
			if(Mage::getStoreConfig('reviewemail/second_email/second_upsell_block', $storeId) > 0) {
				
				$items = array(); 
				$upsell_items = array();
		
				foreach($order->getAllItems() as $_item) {	
					$items[] = array('id' => $_item->getProductId(), 'price' => $_item->getPrice());
				}
				
				krsort($items);
		
				foreach ($items as $item) {
					$product = Mage::getModel('catalog/product')->load($item['id']);
					$upsell_products = $product->getUpsellProducts(); 
					foreach($upsell_products as $upsell) {
						$upsell_product = Mage::getModel('catalog/product')->load($upsell->getId());				
						if($upsell_product->getStatus() == '1'){
							$upsell_items[] = array('id' => $upsell->getId());				
						}
					}
				}
		
				if(count($upsell_items) > 0) {
					$upsell_block = '1';
					$upsell_block_title = Mage::getStoreConfig('reviewemail/second_email/second_upsell_title', $storeId);					
				}
			}			
		}
		
		// ============================================
		// ============= Footer block ================= 
		
		$footer_block = ''; 
		$footer_block_content = '';	
		
		if($email_id == '1') {
			$display = 	Mage::getStoreConfig('reviewemail/first_email/first_footer_block', $storeId);
			if($display == '1') {
				$footer_block = '1';
				if(Mage::getStoreConfig('reviewemail/first_email/first_footer_block_static', $storeId)) {
					$static_block = Mage::getModel('cms/block')->setStoreId($storeId)->load(Mage::getStoreConfig('reviewemail/first_email/first_footer_block_static', $storeId));	
					if($static_block->getIsActive()) { 
						$footer_block_content = $static_block->getContent(); 
					}
				}
			}
			if($display == '2') {
				$footer_block = '1';
				$footer_block_content = nl2br(Mage::getStoreConfig('reviewemail/first_email/first_footer_block_text', $storeId)); 	
			}
		} else {
			$display = 	Mage::getStoreConfig('reviewemail/second_email/second_footer_block', $storeId); 	
			if($display == '1') {
				$footer_block = '1';
				if(Mage::getStoreConfig('reviewemail/second_email/second_footer_block_static', $storeId)) {
					$static_block = Mage::getModel('cms/block')->setStoreId($storeId)->load(Mage::getStoreConfig('reviewemail/second_email/second_footer_block_static', $storeId));	
					if($static_block->getIsActive()) { 
						$footer_block_content = $static_block->getContent(); 
					}
				}
			}
			if($display == '2') {
				$footer_block = '1';
				$footer_block_content = nl2br(Mage::getStoreConfig('reviewemail/second_email/second_footer_block_text', $storeId)); 	
			}
		}

		// ===============================================
		// ================= TEMPLATE ==================== 

		if($email_id == '1') {
			$title = Mage::getStoreConfig('reviewemail/first_email/first_title', $storeId); 	
		} else {
			$title = Mage::getStoreConfig('reviewemail/second_email/second_title', $storeId); 			
		}
		
		$title = str_replace("{{orderid}}",$order->getIncrementId(), $title);

		if($email_id == '1') {
			$templateId = Mage::getStoreConfig('reviewemail/first_email/email_template', $storeId);		
			$color = '#' . Mage::getStoreConfig('reviewemail/first_email/reviewemail_color', $storeId);		
			$textcolor = '#' . Mage::getStoreConfig('reviewemail/first_email/reviewemail_color_text', $storeId);				
			$email_logo = Mage::getStoreConfig('reviewemail/first_email/reviewemail_logo', $storeId);
			$unsubscribe_link = Mage::getStoreConfig('reviewemail/first_email/reviewemail_unsubscribe', $storeId);		
		} else {
			$templateId = Mage::getStoreConfig('reviewemail/second_email/email_template', $storeId);				
			$color = '#' . Mage::getStoreConfig('reviewemail/second_email/reviewemail_color', $storeId);		
			$textcolor = '#' . Mage::getStoreConfig('reviewemail/second_email/reviewemail_color_text', $storeId);				
			$email_logo = Mage::getStoreConfig('reviewemail/second_email/reviewemail_logo', $storeId);
			$unsubscribe_link = Mage::getStoreConfig('reviewemail/second_email/reviewemail_unsubscribe', $storeId);		
		}

		if($email_logo) {
			$logo = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'uploaddir/' . $email_logo; 
		} else {
			$logo = $url . 'skin/frontend/default/default/images/logo_email.gif';
		}	

		// Set variables that can be used in email template
		$vars = array(	'website_name' 				=> $store->getWebsite()->getName(),						
						'group_name'   				=> $store->getGroup()->getName(),
						'store_name'   				=> $store_name, 
						'base_url'					=> Mage::app()->getStore()->getBaseUrl(),
						'order_id'    				=> $order->getIncrementId(),
						'order'   	  				=> $order,
						'store_url'    				=> $url,
						'logo'	  	   				=> $logo,
						'email'						=> $customerEmail,
						'title'						=> $title,
						'welcome_block'				=> $welcome_block,
						'welcome_block_content'		=> $welcome_block_content,
						'review_block'				=> $review_block,
						'review_block_title'		=> $review_block_title,
						'shopreview_block'			=> $shopreview_block,
						'shopreview_block_content'	=> $shopreview_block_content,
						'shopreview_block_title'	=> $shopreview_block_title,
						'specs_block'				=> $specs_block,
						'specs_block_content'		=> $specs_block_content,
						'specs_block_title'			=> $specs_block_title,						
						'crosssel_block'			=> $crosssel_block,
						'crosssel_block_title'		=> $crosssel_block_title,
						'upsell_block'				=> $upsell_block,
						'upsell_block_title'		=> $upsell_block_title,
						'footer_block'				=> $footer_block,
						'footer_block_content'		=> $footer_block_content,
						'color'		   				=> $color,
						'emailid'					=> $email_id,
						'textcolor'		   			=> $textcolor,
						'unsubscribe_link'			=> $unsubscribe_link,
						'customer_name'				=> $customerName,
						'reviewid'					=> $reviewemailId);					
	
		$translate  = Mage::getSingleton('core/translate');

		if($bcc_email && $bcc_enabled) {	
			Mage::getModel('core/email_template')->addBcc($bcc_email)->sendTransactional($templateId, $sender, $customerEmail, $customerName, $vars, $storeId);
		} else {
			Mage::getModel('core/email_template')->sendTransactional($templateId, $sender, $customerEmail, $customerName, $vars, $storeId);		
		}

		$translate->setTranslateInline(true);
		Mage::app()->setCurrentStore($oldStore);				
		return;
  	}
  
 
	public function loadByOrderId($order_id, $email_id = '1') {
		$collection = Mage::getModel('reviewemail/reviewemail')->getCollection()
					->addFieldToFilter('order_id', array('eq' => $order_id))
					->addFieldToFilter('email_id', array('eq' => $email_id));
        $collection = $collection->getFirstItem();
        return $collection;
    } 

	public function loadByReviewId($reviewemail_id) {
        $this->_getResource()->load($this, $reviewemail_id, 'reviewemail_id');
        return $this;
    } 


	public function loadByEmail($email) {
        $this->_getResource()->load($this, $email, 'customer_email');
        return $this;
    } 
      
}