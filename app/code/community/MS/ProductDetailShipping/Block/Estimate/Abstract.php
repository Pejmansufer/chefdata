<?php
/**
 * Shipping Calcutalor
 *
 * Website: www.hiremagento.com 
 * Email: hiremagento@gmail.com
 */
abstract class MS_ProductDetailShipping_Block_Estimate_Abstract extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * Estimate model
     *
     * @var MS_ProductDetailShipping_Model_Estimate
     */
    protected $_estimate = null;


    /**
     * Config model
     *
     * @var MS_ProductDetailShipping_Model_Config
     */
    protected $_config = null;


    /**
     * Module session model
     *
     * @var $_session MS_ProductDetailShipping_Model_Session
     */
    protected $_session = null;

    /**
     * List of carriers
     *
     * @var array
     */
    protected $_carriers = null;

    /**
     * Retrieve estimate data model
     *
     * @return MS_ProductDetailShipping_Model_Estimate
     */
    public function getEstimate()
    {
        if ($this->_estimate === null) {
            $this->_estimate = Mage::getSingleton('ms_productdetailshipping/estimate');
        }

        return $this->_estimate;
    }

    /**
     * Retrieve configuration model for module
     *
     * @return MS_ProductDetailShipping_Model_Config
     */
    public function getConfig()
    {
        if ($this->_config === null) {
            $this->_config = Mage::getSingleton('ms_productdetailshipping/config');
        }

        return $this->_config;
    }

    /**
     * Retrieve session model object
     *
     * @return MS_ProductDetailShipping_Model_Session
     */
    public function getSession()
    {
        if ($this->_session === null) {
            $this->_session = Mage::getSingleton('ms_productdetailshipping/session');
        }

        return $this->_session;
    }

    /**
     * Check is enabled functionality
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->getConfig()->isEnabled() && !$this->getProduct()->isVirtual();
    }
}
