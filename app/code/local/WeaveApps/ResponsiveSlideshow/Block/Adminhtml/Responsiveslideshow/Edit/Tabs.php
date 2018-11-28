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
class WeaveApps_ResponsiveSlideshow_Block_Adminhtml_Responsiveslideshow_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('responsiveslideshow_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('responsiveslideshow')->__('Slide Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('responsiveslideshow')->__('Slide Information'),
          'title'     => Mage::helper('responsiveslideshow')->__('Slide Information'),
          'content'   => $this->getLayout()->createBlock('responsiveslideshow/adminhtml_responsiveslideshow_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}