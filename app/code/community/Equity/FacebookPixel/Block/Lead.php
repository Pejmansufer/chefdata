<?php
class Equity_FacebookPixel_Block_Lead extends Equity_FacebookPixel_Block_Abstract
{

    protected function _isLeadEnabled() {
        return $this->_getConfigHelper()->isLeadEnabled();
    }

    protected function _canShow() {
        if (!$this->_isLeadEnabled() || !$this->getContactSubmitReport()) {

            return false;
        }

        return true;
    }

    public function getContactSubmitReport() {
        $session = Mage::getSingleton('core/session', array('name' => 'frontend'));
        return $session->getIsContactSubmit(true);
    }

    protected function _toHtml() {

        $html = parent::_toHtml();

        if ($this->getContactSubmitReport()) {
            $this->_resetContactSubmitReport();
        }

        return $html;
    }

    protected function _resetContactSubmitReport() {
        Mage::getSingleton('core/session')->setIsContactSubmit(0);
    }

}
