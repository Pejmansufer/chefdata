<?php
/**
 * WeaveApps_ResponsiveSlideshow Extension
 *
 * @category   WeaveApps
 * @package    WeaveApps_ResponsiveSlideshow
 * @copyright  Copyright (c) 2014 Weave Apps. (http://www.weaveapps.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *
 */
class WeaveApps_ResponsiveSlideshow_Block_Adminhtml_Responsiveslideshow_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('responsiveslideshow_form', array('legend'=>Mage::helper('responsiveslideshow')->__('Slide information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('responsiveslideshow')->__('Title'),
          'required'  => true,
          'name'      => 'title',
      ));
	  
      $fieldset->addField('banner_type', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('Type'),
            'name' => 'banner_type',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('responsiveslideshow')->__('Image'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('responsiveslideshow')->__('Youtube Video'),
                ),
                 array(
                    'value' => 2,
                    'label' => Mage::helper('responsiveslideshow')->__('Vimeo Video'),
                ),
            ),
        ));


      $fieldset->addField('video_id', 'text', array(
          'label'     => Mage::helper('responsiveslideshow')->__('Video ID'),
          'required'  => false,
          'name'      => 'video_id',
          'note'=> 'enter the video id of your YouTube or Vimeo video (not the full link)',
      ));


        $fieldset->addField('video_height', 'text', array(
          'label'     => Mage::helper('responsiveslideshow')->__('Video height'),
          'required'  => false,
          'name'      => 'video_height',
      ));


      $fieldset->addField('auto_play', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('Auto play'),
            'name' => 'auto_play',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('responsiveslideshow')->__('Enabled'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('responsiveslideshow')->__('Disabled'),
                ),
            ),
        ));


    $fieldset->addField('image', 'file', array(
          'label'     => Mage::helper('responsiveslideshow')->__('Image'),
          'required'  => false,
          'name'      => 'image',
	  ));
	  
	  $fieldset->addField('sort_order', 'text', array(
          'label'     => Mage::helper('responsiveslideshow')->__('Sort order'),
          'required'  => true,
          'name'      => 'sort_order',
          'id'      => 'sort_order',
          'note' => 'Numeric only. This will be used for sorting.'
      ));


    $fieldset->addField('link', 'text', array(
          'label'     => Mage::helper('responsiveslideshow')->__('URL'),
          'required'  => false,
          'name'      => 'link',
          'note' => 'Slide link URL. If left empty, it will no be linked.'
      ));


    $fieldset->addField('link_target', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('URL Target'),
            'name' => 'link_target',
            'required'  => false,
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('responsiveslideshow')->__('New Window'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('responsiveslideshow')->__('Same Window'),
                ),
            ),
        ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('responsiveslideshow')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('responsiveslideshow')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('responsiveslideshow')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('caption', 'editor', array(
          'name'      => 'caption',
          'label'     => Mage::helper('responsiveslideshow')->__('caption'),
          'title'     => Mage::helper('responsiveslideshow')->__('Caption'),
          'style'     => 'width:400px; height:100px;',
          'wysiwyg'   => false,
          'required'  => false,
          'note' => 'Enter a few words about your slide. (No more than 100 words is suggested).'
      ));


      $fieldset->addField('caption_bg_colour','text',array(
      'label' => Mage::helper('responsiveslideshow')->__('Caption background color'),
      'required' => false,
      'name' => 'caption_bg_colour',
      'id' => 'caption_bg_colour',
      )
      );


      $fieldset->addField('caption_font_colour','text',array(
      'label' => Mage::helper('responsiveslideshow')->__('Caption font color'),
      'required' => false,
      'name' => 'caption_font_colour',
      'id' => 'caption_font_colour',
      )
      );

      $fieldset->addField('caption_position', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('Positioning'),
            'class' => 'required-entry',
            'name' => 'caption_position',
            'note'=> 'choose where to display your text/caption or select none to hide it completely.',
            'values' => array(
                array(
                    'value' => 'hidden',
                    'label' => Mage::helper('responsiveslideshow')->__('None'),
                ),
                array(
                    'value' => 'left',
                    'label' => Mage::helper('responsiveslideshow')->__('Left'),
                ),
                  array(
                    'value' => 'right',
                    'label' => Mage::helper('responsiveslideshow')->__('Right'),
                ),
                     array(
                    'value' => 'bottom',
                    'label' => Mage::helper('responsiveslideshow')->__('Bottom'),
                ),
            ),
        ));

      if (Mage::getSingleton('adminhtml/session')->getResponsiveSlideshowData())
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getResponsiveSlideshowData());
          Mage::getSingleton('adminhtml/session')->setResponsiveSlideshowData(null);
      } elseif ( Mage::registry('responsiveslideshow_data') ) {
          $form->setValues(Mage::registry('responsiveslideshow_data')->getData());
      }
      return parent::_prepareForm();
  }
}