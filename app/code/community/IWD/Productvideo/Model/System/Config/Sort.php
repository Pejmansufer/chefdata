<?php
class IWD_Productvideo_Model_System_Config_Sort
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'before', 'label' => 'Before (Image - Video)'),
            array('value' => 'after', 'label' => 'After  (Video - Image)'),
        );
    }
}