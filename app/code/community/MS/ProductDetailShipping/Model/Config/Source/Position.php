<?php
/**
 * Shipping Calcutalor
 *
 * Website: www.hiremagento.com 
 * Email: hiremagento@gmail.com
 */
class MS_ProductDetailShipping_Model_Config_Source_Position
{
    /**
     * Return list of options for the system configuration field.
     * These options indicate the position of the form block on the page
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => MS_ProductDetailShipping_Model_Config::DISPLAY_POSITION_LEFT,
                'label' => Mage::helper('ms_productdetailshipping')->__('Left Column')
            ),
            array(
                'value' => MS_ProductDetailShipping_Model_Config::DISPLAY_POSITION_RIGHT,
                'label' => Mage::helper('ms_productdetailshipping')->__('Right Column')
            ),
            array(
                'value' => MS_ProductDetailShipping_Model_Config::DISPLAY_POSITION_CUSTOM,
                'label' => Mage::helper('ms_productdetailshipping')->__('Custom Position')
            ),
        );
    }
}
