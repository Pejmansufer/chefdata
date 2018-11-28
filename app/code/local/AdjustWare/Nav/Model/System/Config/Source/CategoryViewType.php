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
class AdjustWare_Nav_Model_System_Config_Source_CategoryViewType extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array();

        $options[] = array(
            'value'=> 'default',
            'label' => Mage::helper('adjnav')->__('Default')
        );
        $options[] = array(
            'value'=> 'dropdown',
            'label' => Mage::helper('adjnav')->__('Dropdown')
        );
        $options[] = array(
            'value'=> 'whole_tree',
            'label' => Mage::helper('adjnav')->__('Whole Categories Tree')
        );

        return $options;
    }
}