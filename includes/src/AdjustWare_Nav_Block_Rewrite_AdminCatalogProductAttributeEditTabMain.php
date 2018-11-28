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
class AdjustWare_Nav_Block_Rewrite_AdminCatalogProductAttributeEditTabMain extends Mage_Adminhtml_Block_Catalog_Product_Attribute_Edit_Tab_Main
{
    /**
     * Adding product form elements for editing attribute
     *
     * @return Mage_Adminhtml_Block_Catalog_Product_Attribute_Edit_Tab_Main
     */
    protected function _prepareForm()
    {
        $result = parent::_prepareForm();
        if(version_compare(Mage::getVersion(),'1.4.2','<' ))
        {
            $attributeObject = $this->getAttributeObject();
            $form = $this->getForm();
            Mage::dispatchEvent('adminhtml_catalog_product_attribute_edit_prepare_form', array(
                'form'      => $form,
                'attribute' => $attributeObject
            ));
        }
        return $result;
    }
}