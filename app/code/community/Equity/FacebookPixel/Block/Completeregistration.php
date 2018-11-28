<?php
class Equity_FacebookPixel_Block_CompleteRegistration extends Equity_FacebookPixel_Block_Abstract
{

    private function checkReferrer()
    {
        if( !isset($_SERVER['HTTP_REFERER']) ) return false;

        $ref = $_SERVER['HTTP_REFERER'];
        $url = parse_url($ref);

        if($url['path'] != "/customer/account/create/") return false;

        return true;
    }

    protected function _canShow() {

        return ($this->_getConfigHelper()->isCompleteRegistrationEnabled() && $this->checkReferrer()) ? true : false;
    }

}
