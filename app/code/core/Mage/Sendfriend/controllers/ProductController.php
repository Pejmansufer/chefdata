<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Sendfriend
 * @copyright  Copyright (c) 2006-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Email to a Friend Product Controller
 *
 * @category    Mage
 * @package     Mage_Sedfriend
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Sendfriend_ProductController extends Mage_Core_Controller_Front_Action
{
    /**
     * Predispatch: check is enable module
     * If allow only for customer - redirect to login page
     *
     * @return Mage_Sendfriend_ProductController
     */
    public function preDispatch()
    {
        parent::preDispatch();

        /* @var $helper Mage_Sendfriend_Helper_Data */
        $helper = Mage::helper('sendfriend');
        /* @var $session Mage_Customer_Model_Session */
        $session = Mage::getSingleton('customer/session');

        if (!$helper->isEnabled()) {
            $this->norouteAction();
            return $this;
        }

        if (!$helper->isAllowForGuest() && !$session->authenticate($this)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            if ($this->getRequest()->getActionName() == 'sendemail') {
                $session->setBeforeAuthUrl(Mage::getUrl('*/*/send', array(
                    '_current' => true
                )));
                Mage::getSingleton('catalog/session')
                    ->setSendfriendFormData($this->getRequest()->getPost());
            }
        }

        return $this;
    }

    /**
     * Initialize Product Instance
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _initProduct()
    {
        $productId  = (int)$this->getRequest()->getParam('id');
        if (!$productId) {
            return false;
        }
        $product = Mage::getModel('catalog/product')
            ->load($productId);
        if (!$product->getId() || !$product->isVisibleInCatalog()) {
            return false;
        }

        Mage::register('product', $product);
        return $product;
    }

    /**
     * Initialize send friend model
     *
     * @return Mage_Sendfriend_Model_Sendfriend
     */
    protected function _initSendToFriendModel()
    {
        $model  = Mage::getModel('sendfriend/sendfriend');
        $model->setRemoteAddr(Mage::helper('core/http')->getRemoteAddr(true));
        $model->setCookie(Mage::app()->getCookie());
        $model->setWebsiteId(Mage::app()->getStore()->getWebsiteId());

        Mage::register('send_to_friend_model', $model);

        return $model;
    }

    /**
     * Show Send to a Friend Form
     *
     */
    public function sendAction()
    {
        $product    = $this->_initProduct();
        $model      = $this->_initSendToFriendModel();

        if (!$product) {
            $this->_forward('noRoute');
            return;
        }

        if ($model->getMaxSendsToFriend() && $model->isExceedLimit()) {
            Mage::getSingleton('catalog/session')->addNotice(
                $this->__('The messages cannot be sent more than %d times in an hour', $model->getMaxSendsToFriend())
            );
        }

        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');

        Mage::dispatchEvent('sendfriend_product', array('product' => $product));
        $data = Mage::getSingleton('catalog/session')->getSendfriendFormData();
        if ($data) {
            Mage::getSingleton('catalog/session')->setSendfriendFormData(true);
            $block = $this->getLayout()->getBlock('sendfriend.send');
            if ($block) {
                $block->setFormData($data);
            }
        }

        $this->renderLayout();
    }
	/**
     * Send Price Post Action
     *
     */
	public function sendpriceAction(){
		$product = $this->_initProduct();
		
		$email=$this->getRequest()->getParam('email');
		
		$html="
		<html>
			<head></head>
			<body>
				<table style='width:100%;max-width:800px;margin:0 auto;'>
					<tr>
						<td>
							<img style='width:250px;' src='https://www.cdrestaurantequipment.com/media/logo/default/logo_5.png'>
						</td>
						<td style='text-align: right; vertical-align: top;'>
							<h5 style='margin-bottom:0px;'>Contact Us</h5>
							<h5 style='margin-bottom:0px;margin-top:0px;'>(877) 254-5449</h5>
							<h5 style='margin-top:0px;margin-bottom:0px;'>
								<a href='mailto:info@chefsdeal.com'>info@chefsdeal.com</a>
							</h5>
						</td>
					</tr>
					<tr>
						<td colspan='2' style='background-color:#000;color:#FFF;padding:10px;text-align:center;'>
							Special Price Request
						</td>
					</tr>
					<tr>
						<td colspan='2'>
						<br>
						Dear Customer;<br><br>
						We are happy to offer you this exclusive price, which we guarantee for 24 hours. While the price may not change in that time, we do ask that you request a new quote to ensure you're getting the most current pricing. Order now by clicking the button below or call us at 877-254-5449, Monday through Friday from 8 a.m. to 8 p.m. Eastern Time.
						</td>
					</tr>
					<tr>
						<td  style='text-align: left; vertical-align: top;width:50%;'>
							<img style='max-width:90%;' src='".$product->getImageUrl()."'>
						</td>
						<td style='text-align: left; vertical-align: top;width:50%;'>
							<br>
							<a href='".Mage::getUrl('catalog/product/view/id/'.$product->getId())."'>".Mage::helper('core')->escapeHtml($product->getName())."</a>
							<br>
							<br>
							Retail Price : ".Mage::helper('core')->currency($product->getMsrp(), true, false)."<br>
							<h2 style='color:red;'>Your Price : ".Mage::helper('core')->currency($product->getSpecialPrice(), true, false)."</h2>
							Save ".Mage::helper('core')->currency($product->getMsrp()-$product->getSpecialPrice(), true, false)." by ordering now!
							<a href=".Mage::getUrl('customer/account/login', array('referer' => Mage::helper('core')->urlEncode($product->getUrl())))." style='min-width:200px;display:block;width:100%;color:#FFF;background-color:#D51E2D;padding-top:15px;padding-bottom:15px;margin-top:15px;margin-bottom:15px;text-align:center;text-decoration:none;'>Login & Shop Now</a>
						</td>
					</tr>
					<tr>
						<td colspan='2' style='background-color:#000;color:#FFF;padding:10px;'>
							Note: This special price is valid until 24 hour.<br>
							Thanks for choosing us.
						</td>
					</tr>
				</table>
			</body>
		</html>
		";
		
		$sender=Mage::getStoreConfig('trans_email/ident_general/email');
		$sendername=Mage::getStoreConfig('trans_email/ident_general/name');
		
		$mail = Mage::getModel('core/email');
		$mail->setToName($email);
		$mail->setToEmail($email);
		$mail->setBody($html);
		$mail->setSubject('Chefs Deal Restaurant Equipment '.$product->getName().' Price Request');
		$mail->setFromEmail($sender);
		$mail->setFromName($sendername);
		$mail->setType('html');// YOu can use Html or text as Mail format

		try {
			$mail->send();
			Mage::getSingleton('core/session')->addSuccess('Your price request has been sent to your mail address');
			$this->_redirect($product->getUrlPath());
		}
		catch (Exception $e) {
			Mage::getSingleton('core/session')->addError('Unable to send.');
			$this->_redirect($product->getUrlPath());
		}
	}
	
    /**
     * Send Email Post Action
     *
     */
    public function sendmailAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/send', array('_current' => true));
        }

        $product    = $this->_initProduct();
        $model      = $this->_initSendToFriendModel();
        $data       = $this->getRequest()->getPost();

        if (!$product || !$data) {
            $this->_forward('noRoute');
            return;
        }

        $categoryId = $this->getRequest()->getParam('cat_id', null);
        if ($categoryId) {
            $category = Mage::getModel('catalog/category')
                ->load($categoryId);
            $product->setCategory($category);
            Mage::register('current_category', $category);
        }

        $model->setSender($this->getRequest()->getPost('sender'));
        $model->setRecipients($this->getRequest()->getPost('recipients'));
        $model->setProduct($product);

        try {
            $validate = $model->validate();
            if ($validate === true) {
                $model->send();
                Mage::getSingleton('catalog/session')->addSuccess($this->__('The link to a friend was sent.'));
                $this->_redirectSuccess($product->getProductUrl());
                return;
            }
            else {
                if (is_array($validate)) {
                    foreach ($validate as $errorMessage) {
                        Mage::getSingleton('catalog/session')->addError($errorMessage);
                    }
                }
                else {
                    Mage::getSingleton('catalog/session')->addError($this->__('There were some problems with the data.'));
                }
            }
        }
        catch (Mage_Core_Exception $e) {
            Mage::getSingleton('catalog/session')->addError($e->getMessage());
        }
        catch (Exception $e) {
            Mage::getSingleton('catalog/session')
                ->addException($e, $this->__('Some emails were not sent.'));
        }

        // save form data
        Mage::getSingleton('catalog/session')->setSendfriendFormData($data);

        $this->_redirectError(Mage::getURL('*/*/send', array('_current' => true)));
    }
}
