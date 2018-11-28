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
 
class Magmodules_Reviewemail_Adminhtml_ReviewemailController extends Mage_Adminhtml_Controller_Action {

	protected function _initAction() {
		$this->loadLayout()->_setActiveMenu('reviewemail/items')->_addBreadcrumb(Mage::helper('reviewemail')->__('Items Manager'), Mage::helper('reviewemail')->__('Item Manager'));
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()->renderLayout();
	}

  	public function sendReviewemailAction() {
		$reviewemailIds = $this->getRequest()->getParam('reviewemail');
        if(!is_array($reviewemailIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewemail')->__('Please select item(s)'));
		} else {
            try {
                foreach ($reviewemailIds as $reviewemailId) {
					$model = Mage::getModel('reviewemail/reviewemail');
					$sent = $model->sendReviewemail($reviewemailId);
					$reviewemail = Mage::getModel('reviewemail/reviewemail')->load($reviewemailId);
					$reviewemail->setStatus('sent')->setSentAt(date('Y-m-d H:i:s'))->setUpdatedAt(date('Y-m-d H:i:s'))->save();
					$secondemail = Mage::getModel('reviewemail/reviewemail')->loadByOrderId($reviewemail->getOrderId(), '2'); 
					if(($reviewemail->getEmailId() == '1') && (!$secondemail->getReviewemailId())) {
						if(Mage::getStoreConfig('reviewemail/second_email/second_email_enabled')) {
							$offset = ((int)Mage::getStoreConfig('reviewemail/second_email/reviewemail_days', $reviewemail->getStoreId()) * 86400);
							$save = Mage::getModel('reviewemail/reviewemail');						
							$save->setCustomerName($reviewemail->getName())
								->setEmailId(2)
								->setStoreId($reviewemail->getStoreId())
								->setCustomerName($reviewemail->getCustomerName())
								->setCustomerId($reviewemail->getCustomerId())
								->setOrderId($reviewemail->getOrderId())
								->setCreatedAt(date('Y-m-d H:i:s'))
								->setSheduledAt(date('Y-m-d H:i:s', time() + $offset))
								->setCustomerEmail($reviewemail->getCustomerEmail())
								->setIncrement($reviewemail->getIncrement())       
								->save();										
						}
					}

                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewemail')->__('Total of %d emails were successfully sent and moved to history!', count($reviewemailIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    } 
    
    
	public function sendTestAction() {
		$orderid = Mage::getStoreConfig('reviewemail/testing/test_order');		
		if(!$orderid) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewemail')->__('No ordernumber was specified, save this first!'));
		} else {
			$order = Mage::getModel("sales/order")->loadByIncrementId($orderid);
			if(!$order->getId()) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewemail')->__('No order was found with id: %s!',$orderid));
			} else {
				$model = Mage::getModel('reviewemail/reviewemail');
				$sent = $model->sendReviewemail('', $orderid, '1');
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewemail')->__('Testemail sent to %s', $orderid));
			}
		}
        $this->_redirect('adminhtml/system_config/edit/section/reviewemail');
    } 

	public function sendSecondTestAction() {
		$orderid = Mage::getStoreConfig('reviewemail/testing/test_order');		
		if(!$orderid) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewemail')->__('No ordernumber was specified, save this first!'));
		} else {
			$order = Mage::getModel("sales/order")->loadByIncrementId($orderid);
			if(!$order->getId()) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewemail')->__('No order was found with id: %s!',$orderid));
			} else {
				$model = Mage::getModel('reviewemail/reviewemail');
				$sent = $model->sendReviewemail('', $orderid, '2');
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewemail')->__('Testemail sent to %s', $orderid));
			}
		}
        $this->_redirect('adminhtml/system_config/edit/section/reviewemail');
    }     
    
    public function massDeleteAction()  {   
        $reviewemailIds = $this->getRequest()->getParam('reviewemail');
        if(!is_array($reviewemailIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewemail')->__('Please select item(s)'));
        } else {
            try {
                foreach ($reviewemailIds as $reviewemailId) {
					 $reviewemail = Mage::getModel('reviewemail/reviewemail')->load($reviewemailId);
				     $reviewemail->setStatus('deleted')->setUpdatedAt(date('Y-m-d H:i:s'))->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewemail')->__('Total of %d record(s) were successfully moved to history!', count($reviewemailIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }


	public function sendManualAction() {	
		$order_id = $this->getRequest()->getParam('order_id');
		$reviewemail = Mage::getModel('reviewemail/reviewemail');
		$reviewemail->loadByOrderId($order_id);
		if($reviewemail->getId()) {			
			$status = $reviewemail->getStatus(); 
			$reviewemailId = $reviewemail->getReviewemailId(); 
			if($status == 'scheduled') {
				$reviewemail->sendReviewemail('', $order_id, '1');
				$reviewemail->setStatus('sent')
							->setSentAt(date('Y-m-d H:i:s'))
							->setUpdatedAt(date('Y-m-d H:i:s'))
							->setStatus('sent')					
							->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewemail')->__('Reviewemail has been sent to: %s', $order_id));				
			} else {			
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewemail')->__('Reviewemail has not been sent. Status: %s',$status));					
			}	
		} else {
			$order = Mage::getModel("sales/order")->load($order_id);
			$reviewemail->sendReviewemail('', $order_id, '1');
			$reviewemail->setCustomerName($order->getBillingAddress()->getName())
				->setStoreId($order->getStoreId())
				->setCustomerId($order->getCustomerId())
				->setOrderId($order->getId())
				->setCreatedAt(date('Y-m-d H:i:s'))
				->setSheduledAt(date('Y-m-d H:i:s'))
				->setSentAt(date('Y-m-d H:i:s'))            
				->setCustomerEmail($order->getCustomerEmail())
				->setIncrement($order->getIncrementId()) 
				->setStatus('sent')		               
				->save();		
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewemail')->__('Reviewemail had been sent to: %s', $order_id));					
		}		
	 $this->_redirect('adminhtml/sales_order/view/order_id/' . $order_id);
    } 

	public function sendNowAction() {
		$reviewemail = Mage::getModel('reviewemail/reviewemail')->loadByReviewId($this->getRequest()->getParam('review_id'));
		$sent = $reviewemail->sendReviewemail($reviewemail->getReviewemailId(), '', $reviewemail->getEmailId());
		$reviewemail->setStatus('sent')->setSentAt(date('Y-m-d H:i:s'))->setUpdatedAt(date('Y-m-d H:i:s'))->save();	
		$secondemail = Mage::getModel('reviewemail/reviewemail')->loadByOrderId($reviewemail->getOrderId(), '2'); 
		if(($reviewemail->getEmailId() == '1') && (!$secondemail->getReviewemailId())) {
			if(Mage::getStoreConfig('reviewemail/second_email/second_email_enabled')) {
				$offset = ((int)Mage::getStoreConfig('reviewemail/second_email/reviewemail_days', $reviewemail->getStoreId()) * 86400);
				$save = Mage::getModel('reviewemail/reviewemail');						
				$save->setCustomerName($reviewemail->getName())
					->setEmailId(2)
					->setStoreId($reviewemail->getStoreId())
					->setCustomerName($reviewemail->getCustomerName())
					->setCustomerId($reviewemail->getCustomerId())
					->setOrderId($reviewemail->getOrderId())
					->setCreatedAt(date('Y-m-d H:i:s'))
					->setSheduledAt(date('Y-m-d H:i:s', time() + $offset))
					->setCustomerEmail($reviewemail->getCustomerEmail())
					->setIncrement($reviewemail->getIncrement())       
					->save();										
			}
		}	
		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewemail')->__('Reviewemail has been sent!'));
		$this->_redirect("adminhtml/sales_order/view", array('order_id'=>$reviewemail->getOrderId()));
    }     	

   	public function removeQueAction()  {
		$review_id = $this->getRequest()->getParam('review_id');
		$reviewemail = Mage::getModel('reviewemail/reviewemail')->loadByReviewId($review_id);

		try {
			 $reviewemail = Mage::getModel('reviewemail/reviewemail')->load($reviewemail->getReviewemailId());
			 $reviewemail->setStatus('deleted')->setUpdatedAt(date('Y-m-d H:i:s'))->save();
			 Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewemail')->__('Reviewemail has been deleted from que!'));
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewemail')->__('Unable to to remove email from que'));
		}

		$this->_redirect("adminhtml/sales_order/view", array('order_id'=>$reviewemail->getOrderId()));
    }

   	public function addBackToQueAction() {
		$review_id = $this->getRequest()->getParam('review_id');
		$reviewemail = Mage::getModel('reviewemail/reviewemail')->loadByReviewId($review_id);
		$offset = ((int)Mage::getStoreConfig('reviewemail/first_email/reviewemail_days') * 86400);    

		try {
			$reviewemail = Mage::getModel('reviewemail/reviewemail')->load($reviewemail->getReviewemailId());
			$reviewemail->setStatus('scheduled')->setSheduledAt(date('Y-m-d H:i:s', time() + $offset))->setUpdatedAt(date('Y-m-d H:i:s'))->save();
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewemail')->__('Order has been placed in the que'));
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewemail')->__('Unable to update reviewemail'));
		}
		$this->_redirect("adminhtml/sales_order/view", array('order_id'=>$reviewemail->getOrderId()));
    }    

   	public function addToQueAction() {
		$orderid = $this->getRequest()->getParam('order_id');
		$order = Mage::getModel("sales/order")->load($orderid);
 		$offset = ((int)Mage::getStoreConfig('reviewemail/config/reviewemail_days', $order->getStoreId()) * 86400);
		$name = $order->getBillingAddress()->getName();
		$store = Mage::app()->getStore($order->getStoreId());         
        $savereview = Mage::getModel('reviewemail/reviewemail');
        
		try {
			$savereview->setCustomerName($name)
				->setStoreId($order->getStoreId())
				->setCustomerId($order->getCustomerId())
				->setOrderId($order->getId())
				->setCreatedAt(date('Y-m-d H:i:s'))
				->setSheduledAt(date('Y-m-d H:i:s', time() + $offset))
				->setCustomerEmail($order->getCustomerEmail())
				->setIncrement($order->getIncrementId())       
				->save();
				 Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewemail')->__('Order has been placed in the que'));	 
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewemail')->__('Unable to update reviewemail'));
		}

		$this->_redirect("adminhtml/sales_order/view", array('order_id'=>$orderid));
    }    

	protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('newsletter/reviewemail/schedule');
    }

}