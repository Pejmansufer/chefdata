<?php
class Trustpilot_Reviews_Block_Adminhtml_System_Config_Form_Snippet extends Mage_Adminhtml_Block_System_Config_Form_Field
{
  protected $_url;
  protected $_helper;

  protected function _construct()
  {
      parent::_construct();
      $this->setTemplate('trustpilot/system/config/snippet.phtml');
      $this->_helper = Mage::helper('trustpilot/Data');
      $this->_url = $this->_helper->getGeneralConfigValue('SnippetUrl');
  }

  protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
  {
    return $this->_toHtml();
  }

  public function getButtonHtml()
  {
    $button = $this->getLayout()->createBlock('adminhtml/widget_button')
      ->setData(
          array(
          'id'        => 'snippet_button',
          'label'     => $this->helper('adminhtml')->__('Get TrustBox Code'),
          'onclick'   => 'window.open(\''.$this->_url.'\')'
        )
      );
    return $button->toHtml();
  }
}