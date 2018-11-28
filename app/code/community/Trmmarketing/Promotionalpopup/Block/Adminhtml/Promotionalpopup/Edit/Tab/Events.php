<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */

class Trmmarketing_Promotionalpopup_Block_Adminhtml_Promotionalpopup_Edit_Tab_Events extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      //$fieldset = $form->addFieldset('promotionalpopup_form', array('legend'=>Mage::helper('promotionalpopup')->__('Pop-up Settings')));
	  $fieldsetchained = $form->addFieldset('promotionalpopup_formconversion', array('legend'=>Mage::helper('promotionalpopup')->__('Chained Pop-ups')));
	  $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(); $wysiwygConfig->addData(array( 'add_variables' => false, 'plugins' => array(), 'widget_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index'), 'directives_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive'), 'directives_url_quoted' => preg_quote(Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive')), 'files_browser_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index'), ));
     /*
	  $fieldset->addField('styles', 'editor', array(
          'label'     => Mage::helper('promotionalpopup')->__('Additional Styles'),
          'title'     => Mage::helper('promotionalpopup')->__('Content'),
          'style'     => 'width:700px; height:70px;',
		  'wysiwyg'   => false,
          'required'  => false,
          'name'      => 'styles',
      ));

      
      $fieldset->addField('promotionalpopup_content', 'editor', array(
          'name'      => 'promotionalpopup_content',
          'label'     => Mage::helper('promotionalpopup')->__('Content'),
          'title'     => Mage::helper('promotionalpopup')->__('Content'),
          'style'     => 'width:700px; height:500px;',
		  //'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
          
		  'config'    => $wysiwygConfig,
		  'add_widgets' => true,
          'add_images' => true,
		  'wysiwyg'   => true,
          'required'  => false,
      ));
	  
	  */
	  
	  $fieldsetchained->addField('closechainedpopup_id', 'select', array(
          'label'     => Mage::helper('promotionalpopup')->__('Chained Pop-up No Conversion Close'),
          'name'      => 'closechainedpopup_id',
          'required'  => false,
          'values'    => Mage::getModel('promotionalpopup/chainedpopup')->toOptionArray(),
      ));
	  
	  $fieldsetchained->addField('conversionchainedpopup_id', 'select', array(
          'label'     => Mage::helper('promotionalpopup')->__('Chained Pop-up Conversion Close'),
          'name'      => 'conversionchainedpopup_id',
          'required'  => false,
          'values'    => Mage::getModel('promotionalpopup/chainedpopup')->toOptionArray(),
      ));
	  
     
      if ( Mage::getSingleton('adminhtml/session')->getPopupData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getPopupData());
          Mage::getSingleton('adminhtml/session')->setPopupData(null);
      } elseif ( Mage::registry('promotionalpopup_data') ) {
          $form->setValues(Mage::registry('promotionalpopup_data')->getData());
      }
      return parent::_prepareForm();
  }
}