<?php

class Trustpilot_Reviews_Model_System_Config_Source_Page
{
    public function toOptionArray()
        {
        return array(
            array('value' => 'trustpilot_trustbox_homepage', 'label' => __('Landing')),
            array('value' => 'trustpilot_trustbox_category', 'label' => __('Category')),
            array('value' => 'trustpilot_trustbox_product', 'label' => __('Product'))
        );
    }
}
