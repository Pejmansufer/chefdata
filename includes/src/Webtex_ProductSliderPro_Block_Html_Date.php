<?php

class Webtex_ProductSliderPro_Block_Html_Date extends Mage_Core_Block_Html_Date
{
    protected function _toHtml()
    {
        $displayFormat = Varien_Date::convertZendToStrFtime($this->getFormat(), true, (bool)$this->getTime());

        $html  = '<input type="text" name="' . $this->getName() . '" id="' . $this->getId() . '" ';
        $html .= 'value="' . $this->escapeHtml($this->getValue()) . '" class="' . $this->getClass() . '" ' . $this->getExtraParams() . '/> ';

        $html .= '<img src="' . $this->getImage() . '" alt="' . $this->helper('core')->__('Select Date') . '" class="v-middle" ';
        $html .= 'title="' . $this->helper('core')->__('Select Date') . '" id="' . $this->getId() . '_trig" />';

        $html .=
            '<script type="text/javascript">
        //<![CDATA[
            var calendarSetupObject = {
                inputField  : "' . $this->getId() . '",
                ifFormat    : "' . $displayFormat . '",
                showsTime   : ' . ($this->getTime() ? 'true' : 'false') . ',
                button      : "' . $this->getId() . '_trig",
                align       : "Bl",
                singleClick : true
            }';

        $calendarYearsRange = $this->getYearsRange();
        if ($calendarYearsRange) {
            $html .= '
                calendarSetupObject.range = ' . $calendarYearsRange . '
                ';
        }

        $html .= '
            Calendar.setup(calendarSetupObject);
        //]]>
        </script>';


        return $html;
    }
}