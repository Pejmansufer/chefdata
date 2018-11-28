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
 * @package     Magmodules_Recentreviews
 * @author      Magmodules <info@magmodules.eu>
 * @copyright   Copyright (c) 2015 (http://www.magmodules.eu)
 * @license     http://www.magmodules.eu/license-agreement/  
 * =============================================================
 */
 
class Magmodules_Reviewemail_Block_Form extends Mage_Core_Block_Template {

	public function _prepareLayout() {
		$this->getLayout()->getBlock('head')->setTitle(Mage::helper('reviewemail')->__('Review'));
		$this->setTemplate('reviewemail/form.phtml');
		return parent::_prepareLayout();
	}
	
	public function getBack() {
		return $this->helper('reviewemail')->getBack();
	}

	public function getOrder() {
		return $this->helper('reviewemail')->getOrder();
	}

	public function getRatings() {
        $collection = Mage::getModel('rating/rating')->getResourceCollection()
					->addEntityFilter('product')
					->setPositionOrder()
					->addRatingPerStoreName(Mage::app()->getStore()->getId())
					->setStoreFilter(Mage::app()->getStore()->getId())
					->load()
					->addOptionToItems();
        return $collection;
    }	

	public function getDefaultCheck() {
		return $this->helper('reviewemail')->getDefaultCheck();
	}	

	public function getAllReviewProducts($_order) {
		return $this->helper('reviewemail')->getAllReviewProducts($_order);		
	}

	public function getItemStyle() {
		return $this->helper('reviewemail')->getItemStyle();
	}	
	
}