<?php

class MS_ProductDetailShipping_Model_Resource_Zipcodes extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('ms_productdetailshipping/zipcodes', 'zip');
    }

    public function getStateByCountry($zipcode, $country)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), 'state')
            ->where('zip = ?', $zipcode)
            ->where('country = ?', $country)
            ->limit(1);

        return $this->_getReadAdapter()->fetchOne($select);
    }
}
