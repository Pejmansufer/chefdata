<?php
/**
 * Layered Navigation Pro
 *
 * @category:    AdjustWare
 * @package:     AdjustWare_Nav
 * @version      2.5.11
 * @license:     HxObcXe62uQsQGs98TzFPLxwiM3aCakojlW7nJdmvI
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
class AdjustWare_Nav_Model_System_Config_Source_BlockView extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array();

        $options[] = array(
            'value'=> 'sidebar',
            'label' => Mage::helper('adjnav')->__('Sidebar')
        );
        $options[] = array(
            'value'=> 'top',
            'label' => Mage::helper('adjnav')->__('Top')
        );

        return $options;
    }
}