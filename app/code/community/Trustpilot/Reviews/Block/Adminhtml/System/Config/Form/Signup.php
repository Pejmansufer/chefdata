<?php
class Trustpilot_Reviews_Block_Adminhtml_System_Config_Form_Signup extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected $_signUpUrl;
    protected $_helper;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('trustpilot/system/config/signup.phtml');
        $this->_helper    = Mage::helper('trustpilot/Data');
        $this->_signUpUrl = $this->_helper->getGeneralConfigValue('SignUpUrl');
    }
    /**
     * Return element html
     *
     * @param  Varien_Data_Form_Element_Abstract $element
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
        return $this->_signUpUrl . '?utm_source=magentov1&utm_medium=appstore&utm_campaign=magentov1app';
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
                'id'        => 'signup_button',
                'label'     => $this->helper('adminhtml')->__('Sign up here'),
                'onclick'   => 'window.open(\''.$this->getSignUpUrl().'\')'
                )
            );
        return $button->toHtml();
    }
}