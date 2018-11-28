<?php

class Trustpilot_Reviews_Model_System_Config_Source_Position
{
 public function toOptionArray()
 {
    return array(
        array('value' => 'before', 'label' => __('Above element')),
        array('value' => 'after', 'label' => __('Below element'))
    );
 }
}
