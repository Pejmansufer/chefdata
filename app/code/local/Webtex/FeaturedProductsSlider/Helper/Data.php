<?php

class Webtex_FeaturedProductsSlider_Helper_Data extends Mage_Core_Helper_Data
{
    public function getCustomerIp()
    {
        $session = new Varien_Object(Mage::getSingleton('core/session')->getValidatorData());
        return $session->getRemoteAddr();
    }

    public function getGeoIP()
    {
        $ip = $this->getCustomerIp();
        //$ip = '87.250.250.3';
        $obj = new Varien_Object();
        $geoipData = array();

        $dbPath = Mage::getBaseDir() . '/lib/GeoIP/GeoIP.dat';
        if (!file_exists($dbPath)){
            return $obj;
        }

        include_once Mage::getBaseDir() . '/lib/GeoIP/geoip.inc';

        $geoip = geoip_open($dbPath, GEOIP_STANDARD);
        $geoipData['code']    = geoip_country_code_by_addr($geoip, $ip);
        $geoipData['country'] = geoip_country_name_by_addr($geoip, $ip);
        geoip_close($geoip);

        $obj->setData($geoipData);
        return $obj;
    }
}