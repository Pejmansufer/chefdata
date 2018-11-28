<?php

class Webtex_ProductSliderPro_Block_Adminhtml_Chooser_Date_To
    extends Webtex_ProductSliderPro_Block_Adminhtml_Chooser_Date
{
    public function getLabel()
    {
        return $this->__('Date To');
    }

    public function getDateHtml()
    {
        $block = $this->getLayout()->createBlock('productsliderpro/html_date')
            ->setName('parameters[date_to]')
            ->setId('date_to')
            ->setClass('')
            ->setFormat(Mage::app()->getLocale()->getDateFormat('long'))
            ->setTime(false)
            ->setImage(Mage::getDesign()->getSkinUrl('images/grid-cal.gif'))
            ->setValue($this->getElement()->getValue());
        return $block->toHtml();
    }
}