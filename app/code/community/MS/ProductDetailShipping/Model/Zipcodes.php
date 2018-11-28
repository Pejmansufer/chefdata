<?php

class MS_ProductDetailShipping_Model_Zipcodes extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('ms_productdetailshipping/zipcodes');
    }

    public function getStateByCountry($zipcode, $country)
    {
        return $this->_getResource()->getStateByCountry($zipcode, $country);
    }
}
