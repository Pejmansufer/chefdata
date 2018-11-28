<?php

class Webtex_ProductSliderPro_Block_Adminhtml_Chooser_Products
    extends Mage_Adminhtml_Block_Template
    implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_element = null;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('productsliderpro/chooser/products.phtml');
    }

    public function setElement(Varien_Data_Form_Element_Abstract $element)
    {
        $this->_element = $element;
        return $this;
    }

    public function getElement()
    {
        return $this->_element;
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }

    public function getProductsChooserUrl()
    {
        return $this->getUrl('*/*/productspro', array('_current' => true));
    }

    public function getSelectHtml()
    {
        $selectBlock = $this->getLayout()->createBlock('core/html_select')
            ->setName('parameters[products][display_mode]')
            ->setId('products_display_mode')
            ->setClass('required-entry page_group_select select')
            ->setExtraParams("onchange=\"ProductsChooserInstance.togglePageGroupChooser(this.value)\"")
            ->setValue($this->getElement()->getValue('display_mode'))
            ->setOptions($this->_getSelectOptions());
        return $selectBlock->toHtml();
    }

    protected function _getSelectOptions()
    {
        $options = array(
            array(
                'value' => 'random',
                'label' => Mage::helper('widget')->__('Random Products')
            ),
            array(
                'value' => 'specific',
                'label' => Mage::helper('widget')->__('Specific Products')
            ),
            array(
                'value' => 'bestsellers',
                'label' => Mage::helper('widget')->__('Bestsellers')
            ),
            array(
                'value' => 'new',
                'label' => Mage::helper('widget')->__('New Products')
            ),
            array(
                'value' => 'most_viewed',
                'label' => Mage::helper('widget')->__('Most Viewed')
            ),
            array(
                'value' => 'most_reviewed',
                'label' => Mage::helper('widget')->__('Most Reviewed')
            )
        );
        return $options;
    }
}