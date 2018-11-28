<?php
/**
 * Shipping Calcutalor
 *
 * Website: www.hiremagento.com 
 * Email: hiremagento@gmail.com
 */
class MS_ProductDetailShipping_Model_Session extends Mage_Core_Model_Session_Abstract
{
    const WI_NAMESPACE = 'productdetailshipping';

    public function __construct()
    {
        $this->init(self::WI_NAMESPACE);
    }
}
