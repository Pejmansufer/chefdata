<?php

class Webtex_ProductSliderPro_Block_Adminhtml_Chooser_Date
    extends Mage_Adminhtml_Block_Template
    implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_element = null;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('productsliderpro/chooser/dates.phtml');
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

    public function getLabel()
    {
        return $this->__('Date');
    }

    public function getDateHtml()
    {
        $block = $this->getLayout()->createBlock('productsliderpro/html_date')
            ->setName('parameters[date]')
            ->setId('date')
            ->setClass('')
            ->setFormat(Mage::app()->getLocale()->getDateFormat('medium'))
            ->setTime(false)
            ->setImage(Mage::getDesign()->getSkinUrl('images/grid-cal.gif'))
            ->setValue($this->getElement()->getValue());
        return $block->toHtml();
    }
}