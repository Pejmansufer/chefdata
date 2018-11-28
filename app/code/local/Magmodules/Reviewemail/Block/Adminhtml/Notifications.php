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
 
class Magmodules_Reviewemail_Block_Adminhtml_Notifications extends Mage_Adminhtml_Block_Template {

    public function getPendingReviews() {        
        if(Mage::getStoreConfig('reviewemail/config/toolbar_block')) {
			$model = Mage::getModel('review/review');
			$collection = $model->getProductCollection()->addStatusFilter($model->getPendingStatus())->load();
			if($collection->count() > 0) {
				return $collection;
			}	
		}		
		return false;
    }

    public function getProductReviewUrl($product) {
        return $this->getUrl('*/catalog_product_review/edit', array('id' => $product->getReviewId()));
    }

}