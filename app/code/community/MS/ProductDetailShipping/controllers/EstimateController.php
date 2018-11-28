<?php
/**
 * Shipping Calcutalor
 *
 * Website: www.hiremagento.com 
 * Email: hiremagento@gmail.com
 */

require_once 'app/code/core/Mage/Catalog/controllers/ProductController.php';

/**
 * Estimate shiping controller, passes the request to estimate model
 * Extended from product controller for supporting of full product initialization
 *
 */
class MS_ProductDetailShipping_EstimateController extends Mage_Catalog_ProductController
{
    /**
     * Estimate action
     *
     * Initializes the product and passes data to estimate model in block
     */
    public function estimateAction()
    {
        $product = $this->_initProduct();
        $this->loadLayout(false);
        $block = $this->getLayout()->getBlock('shipping.estimate.result');
        if ($block) {
            $estimate = $block->getEstimate();
            $product->setAddToCartInfo((array) $this->getRequest()->getPost());
            $estimate->setProduct($product);
            $addressInfo = $this->getRequest()->getPost('estimate');
            $estimate->setAddressInfo((array) $addressInfo);
            $block->getSession()->setFormValues($addressInfo);
            try {
                $estimate->estimate();
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('catalog/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('catalog/session')->addError(
                    Mage::helper('ms_productdetailshipping')->__('There was an error during processing your shipping request')
                );
            }
        }
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }
}
