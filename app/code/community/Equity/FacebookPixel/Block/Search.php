<?php
class Equity_FacebookPixel_Block_Search extends Equity_FacebookPixel_Block_Abstract
{

    protected function _canShow() {
        return $this->_getConfigHelper()->isSearchEnabled();
    }

}
