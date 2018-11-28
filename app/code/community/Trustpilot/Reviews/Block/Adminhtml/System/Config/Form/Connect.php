<?php
class Trustpilot_Reviews_Block_Adminhtml_System_Config_Form_Connect 
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected $_connectUrl;
    protected $_helper;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('trustpilot/system/config/connect.phtml');
        $this->_helper    = Mage::helper('trustpilot');
        $this->_connectUrl = $this->_helper->getGeneralConfigValue('ConnectUrl');
    }
    /**
     * Return element html
     *
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->_toHtml();
    }
    /**
     * Return url for button
     *
     * @return string
     */
    public function getSignUpUrl()
    {

        return $this->_connectUrl;
    }
    /**
     * Generate button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(
                array(
                'id'        => 'connect_button',
                'label'     => $this->helper('adminhtml')->__('Get installation key'),
                'onclick'   => 'window.open(\''.$this->getSignUpUrl().'\')'
                )
            );
        return $button->toHtml();
    }
}