<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */

class Trmmarketing_Promotionalpopup_Block_Adminhtml_Promotionalpopup_Edit_Tab_Template extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
	  
	  $fieldsetcss = $form->addFieldset('promotionalpopup_Mdformfactorsettings', array('legend'=>Mage::helper('promotionalpopup')->__('Pop-up CSS Settings')));
	  
	  $fieldsetcss->addField('css_reset', 'select', array(
          'label'     => Mage::helper('promotionalpopup')->__('CSS Reset'),
          'name'      => 'css_reset',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('promotionalpopup')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('promotionalpopup')->__('Disabled'),
              ),
          ),
      ));
	  
	  $fieldsettime = $form->addFieldset('promotionalpopup_timesettings', array('legend'=>Mage::helper('promotionalpopup')->__('Pop-up Open & Close Delay Settings')));
	  
	  $fieldsettime->addField('timestatus', 'select', array(
          'label'     => Mage::helper('promotionalpopup')->__('Open Delay'),
          'name'      => 'timestatus',
		  'class'     => 'required-entry',
          'required'  => true,
          'values'    => array(
              array(
                  'value'     => 0,
                  'label'     => Mage::helper('promotionalpopup')->__('Open Immediately'),
              ),

              array(
                  'value'     => 5,
                  'label'     => Mage::helper('promotionalpopup')->__('5 Seconds'),
              ),
			  
			  array(
                  'value'     => 10,
                  'label'     => Mage::helper('promotionalpopup')->__('10 Seconds'),
              ),
			  
			  array(
                  'value'     => 15,
                  'label'     => Mage::helper('promotionalpopup')->__('15 Seconds'),
              ),
			  
			  array(
                  'value'     => 30,
                  'label'     => Mage::helper('promotionalpopup')->__('30 Seconds'),
              ),
			  
			  array(
                  'value'     => 60,
                  'label'     => Mage::helper('promotionalpopup')->__('60 Seconds'),
              ),
			  
			  array(
                  'value'     => 120,
                  'label'     => Mage::helper('promotionalpopup')->__('120 Seconds'),
              ),
          ),
      ));
	  
	
	  $fieldsettime->addField('delay', 'select', array(
          'label'     => Mage::helper('promotionalpopup')->__('Auto-close Delay'),
          'name'      => 'delay',
		  'class'     => 'required-entry',
          'required'  => true,
          'values'    => array(
              array(
                  'value'     => 0,
                  'label'     => Mage::helper('promotionalpopup')->__('Disabled'),
              ),

              array(
                  'value'     => 5,
                  'label'     => Mage::helper('promotionalpopup')->__('5 Seconds'),
              ),
			  
			  array(
                  'value'     => 10,
                  'label'     => Mage::helper('promotionalpopup')->__('10 Seconds'),
              ),
			  
			  array(
                  'value'     => 15,
                  'label'     => Mage::helper('promotionalpopup')->__('15 Seconds'),
              ),
			  
			  array(
                  'value'     => 30,
                  'label'     => Mage::helper('promotionalpopup')->__('30 Seconds'),
              ),
			  
			  array(
                  'value'     => 60,
                  'label'     => Mage::helper('promotionalpopup')->__('60 Seconds'),
              ),
			  
			  array(
                  'value'     => 120,
                  'label'     => Mage::helper('promotionalpopup')->__('120 Seconds'),
              ),
          ),
      ));
	  
	  
	  
      $fieldset = $form->addFieldset('promotionalpopup_form', array('legend'=>Mage::helper('promotionalpopup')->__('Default Close Button Settings')));
     
	  
	  $fieldset->addField('template', 'select', array(
          'label'     => Mage::helper('promotionalpopup')->__('Close Button'),
          'name'      => 'template',
		  'onclick'=>'modifyTargetElement(this)',
		  'onchange'=>'modifyTargetElement(this)',
          'values'    => array(
              array(
                  'value'     => 'blank',
                  'label'     => Mage::helper('promotionalpopup')->__('Enable'),
              ),
			  
			  array(
                  'value'     => 'blank_no_close',
                  'label'     => Mage::helper('promotionalpopup')->__('Disable'),
              ),

             
          ),
      ));
	  
	  $fieldset->addField('filename_close_btn', 'image', array(
          'label'     => Mage::helper('promotionalpopup')->__('Close Button Image'),
          'required'  => false,
          'name'      => 'filename_close_btn',
      ));
	  
	  $fieldset->addField('close_btn_label', 'text', array(
          'label'     => Mage::helper('promotionalpopup')->__('Close Label'),
          'required'  => false,
          'name'      => 'close_btn_label',
      ));
	  
	  $fieldset->addField('close_label_color', 'text', array(
          'label'     => Mage::helper('promotionalpopup')->__('Label Color'),
		  'class'  => 'color {hash:true,required:false}',
          'required'  => false,
          'name'      => 'close_label_color',
      ));
	  
	  $fieldset->addField('close_btn_position', 'select', array(
          'label'     => Mage::helper('promotionalpopup')->__('Close Button Position'),
          'name'      => 'close_btn_position',
		  'onclick'=>'modifyTargetElement(this)',
		  'onchange'=>'modifyTargetElement(this)',
          'values'    => array(
              array(
                  'value'     => 'topleft',
                  'label'     => Mage::helper('promotionalpopup')->__('Top Left'),
              ),
			  /*
			  array(
                  'value'     => 'left:50%; top:0px;',
                  'label'     => Mage::helper('promotionalpopup')->__('Top Center'),
              ),
			  */
			  array(
                  'value'     => 'topright',
                  'label'     => Mage::helper('promotionalpopup')->__('Top Right'),
              ),
			  
			  array(
                  'value'     => 'bottomleft',
                  'label'     => Mage::helper('promotionalpopup')->__('Bottom Left'),
              ),
			  /*
			  array(
                  'value'     => 'left:50%; bottom:0px;',
                  'label'     => Mage::helper('promotionalpopup')->__('Bottom Center'),
              ),
			  */
			  array(
                  'value'     => 'bottomright',
                  'label'     => Mage::helper('promotionalpopup')->__('Bottom Right'),
              ),

             
          ),
      ));
	  
	
	  $fieldset->addField('close_btn_horizontal_offset', 'text', array(
          'label'     => Mage::helper('promotionalpopup')->__('Horizontal Offset'),
          'required'  => false,
          'name'      => 'close_btn_horizontal_offset',
      ));
	  
	  
	  $fieldset->addField('close_btn_vertical_offset', 'text', array(
          'label'     => Mage::helper('promotionalpopup')->__('Vertical Offset'),
          'required'  => false,
          'name'      => 'close_btn_vertical_offset',
      ));
	  
	  
	  
		/*
		
	  
	
	  $fieldset->addField('width', 'text', array(
          'label'     => Mage::helper('promotionalpopup')->__('Width'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'width',
      ));
	  
	  
	  $fieldset->addField('height', 'text', array(
          'label'     => Mage::helper('promotionalpopup')->__('Height'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'height',
      ));
	  */
	  
	  
	  
	  $fieldsetmodal = $form->addFieldset('promotionalpopup_modal', array('legend'=>Mage::helper('promotionalpopup')->__('Modal Background Settings')));
     
	 
	 $fieldsetmodal->addType('Videoupload','Trmmarketing_Promotionalpopup_Lib_Varien_Data_Form_Element_Videoupload');
	 $fieldsetmodal->addField('modal_video_mp4', 'Videoupload', array(
          'label'     => Mage::helper('promotionalpopup')->__('Modal Video Background MP4'),
          'required'  => false,
          'name'      => 'modal_video_mp4',
      ));
	  
	  $fieldsetmodal->addField('modal_video_ogv', 'Videoupload', array(
          'label'     => Mage::helper('promotionalpopup')->__('Modal Video Background OGV'),
          'required'  => false,
          'name'      => 'modal_video_ogv',
      ));
	  
	  $fieldsetmodal->addField('modal_video_loop', 'select', array(
          'label'     => Mage::helper('promotionalpopup')->__('Video Loop'),
          'name'      => 'modal_video_loop',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('promotionalpopup')->__('Enabled'),
              ),

              array(
                  'value'     => 0,
                  'label'     => Mage::helper('promotionalpopup')->__('Disabled'),
              ),
          ),
      ));
	  
		
		$fieldsetmodal->addField('modal_background', 'image', array(
          'label'     => Mage::helper('promotionalpopup')->__('Modal Background'),
          'required'  => false,
          'name'      => 'modal_background',
      ));
	  
	  $fieldsetmodal->addField('modal_color', 'text', array(
          'label'     => Mage::helper('promotionalpopup')->__('Modal Color'),
		  'class'  => 'color {hash:true,required:false}',
          'required'  => false,
          'name'      => 'modal_color',
      ));
	  
	  
	  
	  $fieldsetmodal->addField('modal_opacity', 'select', array(
          'label'     => Mage::helper('promotionalpopup')->__('Modal Opacity Override'),
          'name'      => 'modal_opacity',
          'required'  => false,
          'values'    => array(
              array(
                  'value'     => '',
                  'label'     => Mage::helper('promotionalpopup')->__('Use Default'),
              ),

              array(
                  'value'     => '0.1',
                  'label'     => Mage::helper('promotionalpopup')->__('10%'),
              ),
			  
			  array(
                  'value'     => '0.2',
                  'label'     => Mage::helper('promotionalpopup')->__('20%'),
              ),
			  
			  array(
                  'value'     => '0.3',
                  'label'     => Mage::helper('promotionalpopup')->__('30%'),
              ),
			  
			  array(
                  'value'     => '0.4',
                  'label'     => Mage::helper('promotionalpopup')->__('40%'),
              ),
			  
			  array(
                  'value'     => '0.5',
                  'label'     => Mage::helper('promotionalpopup')->__('50%'),
              ),
			  
			  array(
                  'value'     => '0.6',
                  'label'     => Mage::helper('promotionalpopup')->__('60%'),
              ),
			  
			  array(
                  'value'     => '0.7',
                  'label'     => Mage::helper('promotionalpopup')->__('70%'),
              ),
			  
			  array(
                  'value'     => '0.8',
                  'label'     => Mage::helper('promotionalpopup')->__('80%'),
              ),
			  
			  array(
                  'value'     => '0.9',
                  'label'     => Mage::helper('promotionalpopup')->__('90%'),
              ),
			  
			  array(
                  'value'     => '1',
                  'label'     => Mage::helper('promotionalpopup')->__('100%'),
              ),
			  
          ),
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