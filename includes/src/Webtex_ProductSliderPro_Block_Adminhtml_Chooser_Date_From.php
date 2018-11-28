<?php

class Webtex_ProductSliderPro_Block_Adminhtml_Chooser_Date_From
    extends Webtex_ProductSliderPro_Block_Adminhtml_Chooser_Date
{
    public function getLabel()
    {
        return $this->__('Date From');
    }

    public function getDateHtml()
    {
        $block = $this->getLayout()->createBlock('productsliderpro/html_date')
            ->setName('parameters[date_from]')
            ->setId('date_from')
            ->setClass('')
            ->setFormat(Mage::app()->getLocale()->getDateFormat('long'))
            //->setTime(false)
            ->setImage(Mage::getDesign()->getSkinUrl('images/grid-cal.gif'))
            ->setValue($this->getElement()->getValue());
        return $block->toHtml();
    }
}