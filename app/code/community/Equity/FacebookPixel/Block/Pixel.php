<?php
class Equity_FacebookPixel_Block_Pixel extends Equity_FacebookPixel_Block_Abstract
{
    protected function _isPixelEnabled()
    {
        return $this->_getConfigHelper()->isFacebookPixelEnabled();
    }

    public function getPixelId()
    {
        return $this->_getConfigHelper()->getFacebookPixelId();
    }

    protected function _canShow()
    {

        if ( !$this->getPixelId() || !$this->_isPixelEnabled() ) {
            return false;
        }

        $action = Mage::app()->getRequest()->getActionName();
        if($action == 'noRoute'){
            return false;
        }

        return true;
    }

}
