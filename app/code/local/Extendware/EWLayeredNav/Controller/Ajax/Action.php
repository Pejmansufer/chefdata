<?php
abstract class Extendware_EWLayeredNav_Controller_Ajax_Action extends Extendware_EWCore_Controller_Frontend_Action
{
	public function preDispatch()
    {
        if ($this->getFlag('', self::FLAG_NO_COOKIES_REDIRECT) && Mage::getStoreConfig('web/browser_capabilities/cookies')) {
        	$checkCookie = in_array($this->getRequest()->getActionName(), $this->_cookieCheckActions);
            $checkCookie = $checkCookie && !$this->getRequest()->getParam('nocookie', false);
            $cookies = Mage::getSingleton('core/cookie')->get();
            if ($checkCookie && empty($cookies)) {
	        	return $this->noCookiesAction();
            }
        }

        return parent::preDispatch();
    }
    
	public function noCookiesAction()
    {
        $this->getRequest()->setDispatched(true);
        $this->setFlag('', self::FLAG_NO_DISPATCH, true); // ensure no other actions are executed
        $errorMessages = array($this->__('Please refresh the page and try again.'));
		$block = Mage::getSingleton('core/layout')->createBlock('ewlayerednav/dialog_blank');
		$block->addJavascript("alert(" . json_encode($this->__('There was an error. Please refresh the page and try again.')) . ");");
        echo $block->toHtml();
    }
}