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
 
class Magmodules_Reviewemail_FormController extends Mage_Core_Controller_Front_Action {

	const STATUS_ENABLED	= 1;
	const STATUS_DISABLED	= 2;
	const STATUS_PENDING	= 3;

	public function indexAction() {
		if(Mage::getSingleton('core/session')->getOrderId()) {
			$order_id = Mage::getSingleton('core/session')->getOrderId(); 
			$store_id = Mage::app()->getStore()->getStoreId();      
			if(Mage::getStoreConfig('reviewemail/frontend/after_submit', $store_id)) {
				$reviews = Mage::getModel('reviewemail/reviews')->getCollection()->addFieldToFilter('order_id', array('eq' => $order_id));			
				$reviews = $reviews->getFirstItem();
				if($reviews->getId() > 0) {
					$this->_redirect('*/success');
				}       	
			}
			$this->loadLayout();
			$this->renderLayout();
		} else {
			$this->_redirect('/');		
		}
	}

	public function save() {
        $storeId = Mage::app()->getStore()->getStoreId();
        if($data = Mage::getSingleton('review/session')->getFormData(true)) {
            $rating = array();
            if (isset($data['ratings']) && is_array($data['ratings'])) {
                $rating = $data['ratings'];
            }
        } else {
            $data = $this->getRequest()->getPost();
            $rating = $this->getRequest()->getParam('ratings', array());
        }

   		$session = Mage::getSingleton('core/session');		
		$emailid = (int)$this->getRequest()->getParam('emailid');
		$reviewemail = Mage::getModel('reviewemail/reviewemail')->load($emailid);
		$customerid = $reviewemail->getCustomerId();
		$orderid = (int)$this->getRequest()->getParam('orderid');
		$total = 0; 
		$productreview = 0;
		$shopreview_id = '';
		
		// SAVE PRODUCT REVIEWS
		if(isset($data['check'])) {
			foreach ($data['check'] as $id => $value) {
				try {
					$product = Mage::getModel('catalog/product')->setStoreId(Mage::app()->getStore()->getId())->load($data['productid'][$id]);
					$reviewdata = array("validate_rating" => "", "nickname" => $data['nickname'], "title" => $data['title'][$id], "detail" => $data['detail'][$id]);
					$review = Mage::getModel('review/review')->setData($reviewdata);			
					$rating  = $this->getRequest()->getParam('ratings-' . $id, array());

					if($customerid < 1) {
						
						$review->setEntityId(1)
								->setEntityPkValue($product->getId())
								->setStatusId(Mage_Review_Model_Review::STATUS_PENDING)
								->setStoreId(Mage::app()->getStore()->getId())
								->setStores(array(Mage::app()->getStore()->getId()))
								->save();

						foreach ($rating as $ratingId => $optionId) {
							Mage::getModel('rating/rating')->setRatingId($ratingId)->setReviewId($review->getId())->addOptionVote($optionId, $product->getId());
						}
						
					} else {
						
						$review->setEntityId(1)
								->setEntityPkValue($product->getId())
								->setStatusId(Mage_Review_Model_Review::STATUS_PENDING)
								->setCustomerId($customerid)
								->setStoreId(Mage::app()->getStore()->getId())
								->setStores(array(Mage::app()->getStore()->getId()))
								->save();

						foreach ($rating as $ratingId => $optionId) {
							Mage::getModel('rating/rating')->setRatingId($ratingId)->setReviewId($review->getId())->setCustomerId($customerid)->addOptionVote($optionId, $product->getId());
						}					
						
					}
					
					$reviews = Mage::getModel('reviewemail/reviews')
								->setReviewemailId($emailid)
								->setReviewId($review->getId())
								->setOrderId($orderid)								
								->setStoreId($storeId)								
								->setCustomerName($data['nickname'])
								->setCustomerEmail($data['contact'])
								->save();
									
					$review->aggregate();
					$total++;
					$productreview++;
				} catch (Exception $e) {
					$session->setFormData($data);
					$session->addError(Mage::helper('reviewemail')->__('Unable to post the review.' . $e));
				}			
			}
		}
		
		// CHECK IF SHOPREVIEW IS ENABLED AND INSTALLED
		if(isset($data['sr-check'])) {
			if(Mage::getStoreConfig('reviewemail/shopreview/enabled') && Mage::helper('core')->isModuleEnabled('Magmodules_Shopreview')) {
				foreach ($data['sr-check'] as $id => $value) {			
					try {
						$review_summary = $data['review_field1'][$id];
						$review_field2 = ''; $rating_field2 = '';
						$review_field3 = ''; $rating_field3 = '';
						$review_field4 = ''; $rating_field4 = '';
						$review_field5 = ''; $rating_field5 = '';
																				
						if(isset($data['review_field2'][$id])) {
							$review_field2 = $data['review_field2'][$id];
							if(strlen($review_field2 > 0)) {
								$review_summary .= '<hr/>' . $review_field2;
							}	
						} 

						if(isset($data['review_field3'][$id])) {
							$review_field3 = $data['review_field3'][$id];
							if(strlen($review_field3 > 0)) {
								$review_summary .= '<hr/>' . $review_field3;
							}	
						} 

						if(isset($data['review_field4'][$id])) {
							$review_field4 = $data['review_field4'][$id];
							if(strlen($review_field4 > 0)) {
								$review_summary .= '<hr/>' . $review_field4;
							}	
						} 

						if(isset($data['review_field5'][$id])) {
							$review_field5 = $data['review_field5'][$id];
							if(strlen($review_field5 > 0)) {
								$review_summary .= '<hr/>' . $review_field5;
							}	
						} 

						if(isset($data['rating_field2'][$id])) {
							$rating_field2 = $data['rating_field2'][$id];
						} 

						if(isset($data['rating_field3'][$id])) {
							$rating_field3 = $data['rating_field3'][$id];
						} 

						if(isset($data['rating_field4'][$id])) {
							$rating_field4 = $data['rating_field4'][$id];
						} 

						if(isset($data['rating_field5'][$id])) {
							$rating_field5 = $data['rating_field5'][$id];
						} 

						$data['review_summary'] = $review_summary; 

						$shopreviewdata = array("name" => $data['nickname'], "email" => $data['contact'], "review_field1" => $data['review_field1'][$id], "rating_field1" => $data['rating_field1'][0], "review_field2" => $review_field2, "rating_field2" => $rating_field2, "review_field3" => $review_field3, "rating_field3" => $rating_field3, "review_field4" => $review_field4, "rating_field4" => $rating_field4, "review_field5" => $review_field5, "rating_field5" => $rating_field5, "store_id" => Mage::app()->getStore()->getId());
						$shopreview = Mage::getModel('shopreview/shopreview')->setData($shopreviewdata);
						$shopreview->setCreatedTime(date('Y-m-d H:i:s',Mage::getModel('core/date')->timestamp(now())));

						if(Mage::getStoreConfig('shopreview/config/auto_approve', Mage::app()->getStore())) {
							$shopreview->setData('status', 1);
						} else {
							$shopreview->setData('status', 3);
						}
						
						$shopreview->save();						
						$shopreview_id = $shopreview->getShopreviewId();						

						$reviews = Mage::getModel('reviewemail/reviews')
								->setReviewemailId($emailid)
								->setShopreviewId($shopreview_id)
								->setOrderId($orderid)								
								->setStoreId($storeId)								
								->setCustomerName($data['nickname'])
								->setCustomerEmail($data['contact'])
								->save();

						if(!Mage::getStoreConfig('reviewemail/shopreview/disable_email')) {					
							if(Mage::getStoreConfig('shopreview/email/admin')) {
								Mage::getModel('shopreview/shopreview')->sendShopreviewemail('admin', $shopreview->getId());		
							}

							if(Mage::getStoreConfig('shopreview/email/post')) {
								Mage::getModel('shopreview/shopreview')->sendShopreviewemail('post', $shopreview->getId());		
							}
						}
						
						$total++;					

					} catch (Exception $e) {
						Mage::getSingleton('customer/session')->setFormData($data);
						$session->addError(Mage::helper('reviewemail')->__('Unable to post the review.') . $e);
					}								
				}
	  		}
		}

        if(!isset($e)) {        		  
			Mage::getSingleton('customer/session')->setReviewemailCoupon('');	
			if((Mage::getStoreConfig('reviewemail/shopreview/requiredproduct')) && ($productreview < 1)) {			
				Mage::getSingleton('customer/session')->setFormData($this->getRequest()->getPost());
				$session->addError(Mage::helper('reviewemail')->__('At least one product review is required'));
				$this->_redirect('*/form');								
			} else {
				if($total > 0) {
					if(Mage::getStoreConfig('reviewemail/second_email/exclusive', $storeId)) {				
						$secondreview = Mage::getModel('reviewemail/reviewemail')->getCollection()
									->addFieldToSelect('*')					
									->addFieldToFilter('order_id', array('eq' => $orderid))
									->addFieldToFilter('customer_email', array('eq' => $reviewemail->getCustomerEmail()))
									->addFieldToFilter('email_id', 2);
						$secondreview = $secondreview->getFirstItem();
						if($secondreview->getReviewemailId()) {
							if($secondreview->getStatus() == 'scheduled') {
								$secondreview->setStatus('notneeded')->save();
							}
						}
					}
					if(Mage::getStoreConfig('reviewemail/coupon/enable')) {				
						if(Mage::getStoreConfig('reviewemail/coupon/trigger') == 'onpage') {
							$coupon = Mage::getModel('reviewemail/coupons')->getCouponCode($emailid, $orderid);				
							Mage::getSingleton('customer/session')->setReviewemailCoupon($coupon);			
						}	
						if(Mage::getStoreConfig('reviewemail/coupon/trigger') == 'onsubmit') {
							$coupon = Mage::getModel('reviewemail/coupons')->getCouponCode($emailid, $orderid);				
							Mage::getModel('reviewemail/coupons')->sendCouponEmail($emailid, $coupon);		
							Mage::getSingleton('customer/session')->setReviewemailCoupon($coupon);			
						}	
					}
					$this->_redirect('*/success');
				} else {
					Mage::getSingleton('customer/session')->setFormData($this->getRequest()->getPost());
					$session->addError(Mage::helper('reviewemail')->__('Please fill in at least one review'));
					$this->_redirect('*/form');				
				}							
			}	
		} else {
			Mage::getSingleton('customer/session')->setFormData($this->getRequest()->getPost());
			$this->_redirect('*/form');	
		}
		
    }

	public function postAction() {
		$this->save();					
	}

}	
	