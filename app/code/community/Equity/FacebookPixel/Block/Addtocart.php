<?php
class Equity_FacebookPixel_Block_AddToCart extends Equity_FacebookPixel_Block_Abstract
{

    protected function _canShow()
    {
        if( !$this->_getConfigHelper()->isAddToCartEnabled() ) return false;
        if( !Mage::getSingleton('core/session', array('name' => 'frontend'))->getIsAddedToCart(true) ) return false;

        return true;
    }

}