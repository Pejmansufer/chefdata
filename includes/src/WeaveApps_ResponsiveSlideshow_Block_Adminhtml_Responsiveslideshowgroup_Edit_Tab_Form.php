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
class WeaveApps_ResponsiveSlideshow_Block_Adminhtml_Responsiveslideshowgroup_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('responsiveslideshowgroup_form', array('legend' => Mage::helper('responsiveslideshow')->__('General settings')));
        $animations = Mage::getSingleton('responsiveslideshow/status')->getAnimationArray();
        $preAnimations = Mage::getSingleton('responsiveslideshow/status')->getPreAnimationArray();

        $fieldset->addField('group_name', 'text', array(
            'label' => Mage::helper('responsiveslideshow')->__('Group Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'group_name',
        ));

        $fieldset->addField('store_id', 'select', array(
            'name'      => 'store_id',
            'label'     => Mage::helper('responsiveslideshow')->__('Store View'),
            'required'  => true,
            'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
        ));

        $fieldset->addField('custom_odr', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('Sort order of the banners'),
            'class' => 'required-entry',
            'name' => 'custom_odr',
            'values' => array(
                array(
                    'value' => 'asc',
                    'label' => Mage::helper('responsiveslideshow')->__('Ascending'),
                ),
                array(
                    'value' => 'desc',
                    'label' => Mage::helper('responsiveslideshow')->__('Descending'),
                ),
                 array(
                    'value' => 'rand',
                    'label' => Mage::helper('responsiveslideshow')->__('Random'),
                ),
            ),
        ));


        $fieldset->addField('slider_width', 'text', array(
            'label' => Mage::helper('responsiveslideshow')->__('Width'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'slider_width',
        ));

          $fieldset->addField('slider_height', 'text', array(
            'label' => Mage::helper('responsiveslideshow')->__('Height'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'slider_height',
        ));



        $fieldset->addField('width_management', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('Width management'),
            'class' => 'required-entry',
            'name' => 'width_management',
            'values' => array(
                array(
                    'value' => 'responsive',
                    'label' => Mage::helper('responsiveslideshow')->__('Responsive'),
                ),
                array(
                    'value' => 'full',
                    'label' => Mage::helper('responsiveslideshow')->__('Full'),
                ),
                 array(
                    'value' => 'fixed',
                    'label' => Mage::helper('responsiveslideshow')->__('Fixed'),
                ),
            ),
            'note' => 'Responsive - resizes to smaller size but maximum width will be equal to the provided width.<br/>
Full - the same as responsive but maximum width will be equal to its container ignoring the provided width.<br/>
Fixed - width and height are not resized.',
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('Status'),
            'class' => 'required-entry',
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('responsiveslideshow')->__('Enabled'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('responsiveslideshow')->__('Disabled'),
                ),
            ),
        ));

     $fieldset = $form->addFieldset('responsiveslideshowgroup_form_slider_dispaly', array('legend' => Mage::helper('responsiveslideshow')->__('Display settings')));
      
    

        $fieldset->addField('show_nav', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('Show navigation controls'),
            'class' => 'required-entry',
            'name' => 'show_nav',
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


        $fieldset->addField('show_paging', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('Show paging'),
            'class' => 'required-entry',
            'name' => 'show_paging',
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

        $fieldset->addField('show_progress_bar', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('Show progress bar'),
            'class' => '',
            'name' => 'show_progress_bar',
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


      $fieldset->addField('progress_bar_position', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('Positioning'),
            'class' => '',
            'name' => 'progress_bar_position',
            'note'=> 'Position of the progress bar',
            'values' => array(
                array(
                    'value' => 'top',
                    'label' => Mage::helper('responsiveslideshow')->__('Top'),
                ),
                array(
                    'value' => 'bottom',
                    'label' => Mage::helper('responsiveslideshow')->__('Bottom'),
                )
            ),
        ));

      $fieldset->addField('progress_bar_colour','text',array(
      'label' => Mage::helper('responsiveslideshow')->__('Progress bar color'),
      'required' => false,
      'name' => 'progress_bar_colour',
      'id' => 'progress_bar_colour',
      )
      );

        $fieldset->addField('dynamic_height', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('Dynamic height'),
            'class' => 'required-entry',
            'name' => 'dynamic_height',
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

        $fieldset->addField('touch_swipe', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('Touch navigation'),
            'class' => 'required-entry',
            'name' => 'touch_swipe',
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

        $fieldset->addField('pause_on_hover', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('Pouse on hover'),
            'class' => 'required-entry',
            'name' => 'pause_on_hover',
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

        $fieldset->addField('lightbox', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('LightBox'),
            'class' => 'required-entry',
            'name' => 'lightbox',
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
             'note' => 'Uses Magnific Popup for displaying images in lightbox.',
        ));

   $fieldset = $form->addFieldset('responsiveslideshowgroup_form_time', array('legend' => Mage::helper('responsiveslideshow')->__('Schedule')));
   

        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $fieldset->addField('start_time', 'date', array(
          'name'   => 'start_time',
          'label'  => Mage::helper('responsiveslideshow')->__('Slideshow start date'),
          'title'  => Mage::helper('responsiveslideshow')->__('Slideshow start Date'),
          'image'  => $this->getSkinUrl('images/grid-cal.gif'),
          'input_format' => $dateFormatIso,
          'format'       => $dateFormatIso,
          'time' => true,
          'note' => 'leave empty to always show this slideshow',
        ));

        $fieldset->addField('end_time', 'date', array(
          'name'   => 'end_time',
          'label'  => Mage::helper('responsiveslideshow')->__('Slideshow end date'),
          'title'  => Mage::helper('responsiveslideshow')->__('Slideshow end Date'),
          'image'  => $this->getSkinUrl('images/grid-cal.gif'),
          'input_format' => $dateFormatIso,
          'format'       => $dateFormatIso,
          'time' => true,
          'note' => 'leave empty to always show this slideshow',
        ));

    $fieldset = $form->addFieldset('responsiveslideshowgroup_form_slider', array('legend' => Mage::helper('responsiveslideshow')->__('Slideshow settings')));
      
        $fieldset->addField('transition_settings', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('Transition'),
            'name' => 'transition_settings',
            'values' => array(
                array(
                    'value' => 'none',
                    'label' => Mage::helper('responsiveslideshow')->__('none'),
                ),
                array(
                    'value' => 'fade',
                    'label' => Mage::helper('responsiveslideshow')->__('fade'),
                ),
                   array(
                    'value' => 'flipHorz',
                    'label' => Mage::helper('responsiveslideshow')->__('flipHorz'),
                ),
                array(
                    'value' => 'flipVert',
                    'label' => Mage::helper('responsiveslideshow')->__('flipVert'),
                ),
                   array(
                    'value' => 'scrollHorz',
                    'label' => Mage::helper('responsiveslideshow')->__('scrollHorz'),
                ),
                array(
                    'value' => 'scrollVert',
                    'label' => Mage::helper('responsiveslideshow')->__('scrollVert'),
                ),
                   array(
                    'value' => 'shuffle',
                    'label' => Mage::helper('responsiveslideshow')->__('shuffle'),
                ),
                array(
                    'value' => 'tileBlind',
                    'label' => Mage::helper('responsiveslideshow')->__('tileBlind'),
                ),
                   array(
                    'value' => 'fadeout',
                    'label' => Mage::helper('responsiveslideshow')->__('fadeout'),
                ),
                array(
                    'value' => 'tileSlide',
                    'label' => Mage::helper('responsiveslideshow')->__('tileSlide'),
                ),
            ),
        ));

        $fieldset->addField('easing_settings', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('Easing effect'),
            'name' => 'easing_settings',
            'values' => array(
                array(
                    'value' => 'easeInOutSine',
                    'label' => Mage::helper('responsiveslideshow')->__('easeInOutSine'),
                ),
                array(
                    'value' => 'easeInBack',
                    'label' => Mage::helper('responsiveslideshow')->__('easeInBack'),
                ),
                   array(
                    'value' => 'easeOutBack',
                    'label' => Mage::helper('responsiveslideshow')->__('easeOutBack'),
                ),
                array(
                    'value' => 'easeInOutQuint',
                    'label' => Mage::helper('responsiveslideshow')->__('easeInOutQuint'),
                ),
                   array(
                    'value' => 'easeOutQuart',
                    'label' => Mage::helper('responsiveslideshow')->__('easeOutQuart'),
                ),
                array(
                    'value' => 'easeOutExpo',
                    'label' => Mage::helper('responsiveslideshow')->__('easeOutExpo'),
                ),
                   array(
                    'value' => 'easeOutCirc',
                    'label' => Mage::helper('responsiveslideshow')->__('easeOutCirc'),
                ),
                array(
                    'value' => 'fade',
                    'label' => Mage::helper('responsiveslideshow')->__('Custom Animation'),
                ),
            ),
        ));

        $fieldset->addField('slider_interval', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('Slider interval'),
            'name' => 'slider_interval',
            'values' => array(
                array(
                    'value' => '0',
                    'label' => Mage::helper('responsiveslideshow')->__('0'),
                ),
                array(
                    'value' => '4500',
                    'label' => Mage::helper('responsiveslideshow')->__('4.5'),
                ),
                   array(
                    'value' => '5000',
                    'label' => Mage::helper('responsiveslideshow')->__('5.0'),
                ),
                array(
                    'value' => '5500',
                    'label' => Mage::helper('responsiveslideshow')->__('5.5'),
                ),
                   array(
                    'value' => '6000',
                    'label' => Mage::helper('responsiveslideshow')->__('6.0'),
                ),
                array(
                    'value' => '6500',
                    'label' => Mage::helper('responsiveslideshow')->__('6.5'),
                ),
                   array(
                    'value' => '7000',
                    'label' => Mage::helper('responsiveslideshow')->__('7.0'),
                ),
                array(
                    'value' => '7500',
                    'label' => Mage::helper('responsiveslideshow')->__('7.5'),
                ),
                   array(
                    'value' => '8000',
                    'label' => Mage::helper('responsiveslideshow')->__('8.0'),
                ),
                array(
                    'value' => '8500',
                    'label' => Mage::helper('responsiveslideshow')->__('8.5'),
                ),
                      array(
                    'value' => '9000',
                    'label' => Mage::helper('responsiveslideshow')->__('9.0'),
                ),
                            array(
                    'value' => '9500',
                    'label' => Mage::helper('responsiveslideshow')->__('9.5'),
                ),
                                  array(
                    'value' => '10000',
                    'label' => Mage::helper('responsiveslideshow')->__('10'),
                ),
            ),
            'note' => 'Milliseconds. 0 to disable auto scroll.'
        ));


        $fieldset->addField('slider_speed', 'select', array(
            'label' => Mage::helper('responsiveslideshow')->__('Slider speed'),
            'name' => 'slider_speed',
            'values' => array(
                  array(
                    'value' => '0',
                    'label' => Mage::helper('responsiveslideshow')->__('0'),
                ),
                array(
                    'value' => '600',
                    'label' => Mage::helper('responsiveslideshow')->__('0.6'),
                ),
                array(
                    'value' => '800',
                    'label' => Mage::helper('responsiveslideshow')->__('0.8'),
                ),
                   array(
                    'value' => '1000',
                    'label' => Mage::helper('responsiveslideshow')->__('1.0'),
                ),
                array(
                    'value' => '1200',
                    'label' => Mage::helper('responsiveslideshow')->__('1.2'),
                ),
                   array(
                    'value' => '1400',
                    'label' => Mage::helper('responsiveslideshow')->__('1.4'),
                ),
                array(
                    'value' => '1600',
                    'label' => Mage::helper('responsiveslideshow')->__('1.6'),
                ),
                   array(
                    'value' => '1800',
                    'label' => Mage::helper('responsiveslideshow')->__('1.8'),
                ),
                array(
                    'value' => '2000',
                    'label' => Mage::helper('responsiveslideshow')->__('2.0'),
                ),
                   array(
                    'value' => '2200',
                    'label' => Mage::helper('responsiveslideshow')->__('2.2'),
                ),
                array(
                    'value' => '2400',
                    'label' => Mage::helper('responsiveslideshow')->__('2.4'),
                ),
            ),
            'note' => 'Milliseconds.'
        ));




        if (Mage::getSingleton('adminhtml/session')->getresponsiveslideshowgroupData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getresponsiveslideshowgroupData());
            Mage::getSingleton('adminhtml/session')->setresponsiveslideshowgroupData(null);
        } elseif (Mage::registry('responsiveslideshowgroup_data')) {
            $form->setValues(Mage::registry('responsiveslideshowgroup_data')->getData());
        }
        return parent::_prepareForm();
    }
}