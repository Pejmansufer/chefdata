<?php

class Equity_FacebookPixel_Model_System_Config_Source_Dropdown_Values
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'sku',
                'label' => 'Product SKU',
            ),
            array(
                'value' => 'id',
                'label' => 'Product ID',
            ),
        );
    }
}