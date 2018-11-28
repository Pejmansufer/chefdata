<?php
class Equity_FacebookPixel_Block_ViewContent extends Equity_FacebookPixel_Block_Abstract
{

    protected function _canShow() {
        return ($this->_getConfigHelper()->isViewContentEnabled()) ?  true : false;
    }

    protected function _getProduct()
    {
        return Mage::registry('current_product');
    }

}
