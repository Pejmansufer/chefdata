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
 
class Magmodules_Reviewemail_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getModuleEnabled($storeid) {
		if(Mage::getStoreConfig('reviewemail/general/enabled', $storeid)) {
			return true;
		}
    }

    public function getOrder() {
		$order_id = Mage::getSingleton('core/session')->getOrderId();
		$email = Mage::getSingleton('core/session')->getEmail();		
		$collection = Mage::getModel('sales/order')->getCollection()
					->addAttributeToSelect('*')
					->addAttributeToFilter('entity_id', array('eq' => $order_id))
					->addAttributeToFilter('customer_email', array('eq' => $email));
        $order = $collection->getFirstItem();
		if($order->getIncrementId()) {
	 		return $order; 
		}
    }   

    public function getDefaultCheck() {		
		if(Mage::getStoreConfig('reviewemail/frontend/checked')) { 
			return 'checked'; 
		}
    }
    
	public function getAllReviewProducts($_order) {		    	
		$items = array(); 
		$old_id = '';
        foreach ($_order->getAllVisibleItems() as $item) {
            if (!$item->isDeleted() && !$item->getParentItemId()) {
				if($old_id != $item->getProductId()) {
	                $items[] =  $item;
    			}
    			$old_id = $item->getProductId(); 
            }
        }
        return $items;
	} 

	public function getReviewProductId($_item, $_order) {		    	
		$review_bundle = Mage::getStoreConfig('reviewemail/config/review_bundle'); 
		$review_group = Mage::getStoreConfig('reviewemail/config/review_group'); 
		$product_id = '';
		if($review_bundle || $review_group) {						
			$options = $_item->getProductOptions();
			if(isset($options['super_product_config']['product_id'])) {
				$parentId = $options['super_product_config']['product_id'];
				$_parent = Mage::getModel('catalog/product')->load($parentId);												
				if(($_parent->getTypeId() == 'bundle') || ($_parent->getTypeId() == 'grouped')) {						
					if(($_parent->getTypeId() == 'grouped') && $review_group) {
						$product_id = $_parent->getId();
					}					
					if(($_parent->getTypeId() == 'bundle') && $review_bundle) {
						$product_id = $_parent->getId();
					}
				}
			}
		}		
		if($product_id) {
			return $product_id;							
		} else {
			return $_item->getProductId();
		}		
	}
	
	public function getItemStyle() {
		if(Mage::getStoreConfig('reviewemail/frontend/checked')) { 
			return '';
		} else {
			return 'display: none;';
		}	
	}	
		     
}