<?php

class Webtex_ProductSliderPro_Model_System_Config_Source_Country
    extends Mage_Adminhtml_Model_System_Config_Source_Country
{
    public function toOptionArray()
    {
        return parent::toOptionArray(true);
    }
}