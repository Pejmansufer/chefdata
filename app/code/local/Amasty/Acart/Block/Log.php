<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


class Amasty_Acart_Block_Log extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
    /**
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $helper = Mage::helper("amacart");
        $html = $this->_getHeaderHtml($element);
        $url = Mage::helper("adminhtml")->getUrl("adminhtml/amacart_log/download");
        $html.= '<div id="amacart_log_container"></div>';
        $html.= '<a href="' . $url . '" target="blank"><span><span><span>'.$helper->__("Show Log").'</span></span></span></a>&nbsp;&nbsp;&nbsp;';
        $html .= $this->_getFooterHtml($element);

        return $html;
    }
}
