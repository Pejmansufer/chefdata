<?php

class Trustpilot_Reviews_Model_System_Config_Source_Trustbox
{
 public function toOptionArray()
 {
    return array(
        array('value' => 'disabled', 'label' => __('Disabled')),
        array('value' => 'enabled', 'label' => __('Enabled'))
      );
 }
}
