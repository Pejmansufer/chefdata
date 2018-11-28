<?php
/**
 * Shipping Calcutalor
 *
 * Website: www.hiremagento.com 
 * Email: hiremagento@gmail.com
 */
class MS_ProductDetailShipping_Model_Observer
{
    /**
     * Config model
     *
     * @var MS_ProductDetailShipping_Model_Config
     */
    protected $_config = null;

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
     * Layouts initializations observer,
     * add the form block into the position that was specified by the configuration
     *
     * @param Varien_Event_Observer $observer
     */
    public function observeLayoutHandleInitialization(Varien_Event_Observer $observer)
    {
        /* @var $controllerAction Mage_Core_Controller_Varien_Action */
        $controllerAction = $observer->getEvent()->getAction();
        $fullActionName = $controllerAction->getFullActionName();
        if ($this->getConfig()->isEnabled() && in_array($fullActionName, $this->getConfig()->getControllerActions())) {
            if ($this->getConfig()->getDisplayPosition() === MS_ProductDetailShipping_Model_Config::DISPLAY_POSITION_LEFT) {
                // Display the form in the left column on the page
                $controllerAction->getLayout()->getUpdate()->addHandle(
                    MS_ProductDetailShipping_Model_Config::LAYOUT_HANDLE_LEFT
                );
            } elseif ($this->getConfig()->getDisplayPosition() === MS_ProductDetailShipping_Model_Config::DISPLAY_POSITION_RIGHT) {
                // Display the form in the right column on the page
                $controllerAction->getLayout()->getUpdate()->addHandle(
                    MS_ProductDetailShipping_Model_Config::LAYOUT_HANDLE_RIGHT
                );
            }elseif ($this->getConfig()->getDisplayPosition() === MS_ProductDetailShipping_Model_Config::DISPLAY_POSITION_CUSTOM) {
                // Display the form in the right column on the page
                $controllerAction->getLayout()->getUpdate()->addHandle(
                    MS_ProductDetailShipping_Model_Config::LAYOUT_HANDLE_CUSTOM
                );
            }
        }
    }
}
