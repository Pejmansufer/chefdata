<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */

class Trmmarketing_Promotionalpopup_Block_Adminhtml_Promotionalpopup_Edit_Tab_Mdformfactor extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
	  
	  $fieldsetlayout = $form->addFieldset('promotionalpopup_mdformfactorsettings', array('legend'=>Mage::helper('promotionalpopup')->__('Tablet Pop-up Settings')));
	  
	  $fieldsetlayout->addField('status_md', 'select', array(
          'label'     => Mage::helper('promotionalpopup')->__('Tablet Pop-up Status'),
          'name'      => 'status_md',
          'values'    => array(
              array(
                  'value'     => 0,
                  'label'     => Mage::helper('promotionalpopup')->__('Disabled'),
              ),
			  
			  array(
                  'value'     => 1,
                  'label'     => Mage::helper('promotionalpopup')->__('Enabled'),
              ),

              
          ),
      ));
	  
	  $fieldsetlayout->addField('filename_md', 'image', array(
          'label'     => Mage::helper('promotionalpopup')->__('Background Image'),
          'required'  => false,
          'name'      => 'filename_md',
      ));
	  
	  $fieldsetlayout->addField('background_color_md', 'text', array(
          'label'     => Mage::helper('promotionalpopup')->__('Background Color'),
		  'class'  => 'color {hash:true,required:false}',
          'required'  => false,
          'name'      => 'background_color_md',
      ));
	  
	
	  $fieldsetlayout->addField('width_md', 'text', array(
          'label'     => Mage::helper('promotionalpopup')->__('Width'),
          'required'  => false,
          'name'      => 'width_md',
      ));
	  
	  
	  $fieldsetlayout->addField('height_md', 'text', array(
          'label'     => Mage::helper('promotionalpopup')->__('Height'),
          'required'  => false,
          'name'      => 'height_md',
      ));
	  
      $fieldset = $form->addFieldset('promotionalpopup_form', array('legend'=>Mage::helper('promotionalpopup')->__('Tablet Pop-up Content')));
	  $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(); $wysiwygConfig->addData(array( 'add_variables' => false, 'plugins' => array(), 'widget_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index'), 'directives_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive'), 'directives_url_quoted' => preg_quote(Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive')), 'files_browser_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index'), ));
     
	  $fieldset->addField('styles_md', 'editor', array(
          'label'     => Mage::helper('promotionalpopup')->__('Additional Styles'),
          'title'     => Mage::helper('promotionalpopup')->__('Content'),
          'style'     => 'width:700px; height:70px;',
		  'wysiwyg'   => false,
          'required'  => false,
          'name'      => 'styles_md',
      ));

      
      $fieldset->addField('promotionalpopup_content_md', 'editor', array(
          'name'      => 'promotionalpopup_content_md',
          'label'     => Mage::helper('promotionalpopup')->__('Content'),
          'title'     => Mage::helper('promotionalpopup')->__('Content'),
          'style'     => 'width:700px; height:500px;',
		  'config'    => $wysiwygConfig,
		  'add_widgets' => true,
          'add_images' => true,
		  'wysiwyg'   => true,
          'required'  => false,
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