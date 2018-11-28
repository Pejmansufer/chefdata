<?php
class Equity_FacebookPixel_Block_AddToWishlist extends Equity_FacebookPixel_Block_Abstract
{

    protected function _canShow() {
        return $this->_getConfigHelper()->isAddToWishlistEnabled();
    }

}
