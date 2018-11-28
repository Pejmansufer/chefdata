<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */

class Trmmarketing_Promotionalpopup_Block_Adminhtml_Promotionalpopup_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('promotionalpopup_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('promotionalpopup')->__('Pop-up Settings'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('promotionalpopup')->__('Pop-up Setup'),
          'title'     => Mage::helper('promotionalpopup')->__('Pop-up Setup'),
          'content'   => $this->getLayout()->createBlock('promotionalpopup/adminhtml_promotionalpopup_edit_tab_form')->toHtml(),
      ));
	  
	  $this->addTab('template_section', array(
          'label'     => Mage::helper('promotionalpopup')->__('Template & Modal Setup'),
          'title'     => Mage::helper('promotionalpopup')->__('Template & Modal Setup'),
          'content'   => $this->getLayout()->createBlock('promotionalpopup/adminhtml_promotionalpopup_edit_tab_template')->toHtml(),
      ));
	  
	  
	  /*
	  
	  $this->addTab('modal_section', array(
          'label'     => Mage::helper('promotionalpopup')->__('Modal Setup'),
          'title'     => Mage::helper('promotionalpopup')->__('Modal Setup'),
          'content'   => $this->getLayout()->createBlock('promotionalpopup/adminhtml_promotionalpopup_edit_tab_modal')->toHtml(),
      ));
	  */
	  
	  $this->addTab('lgformfactor_section', array(
          'label'     => Mage::helper('promotionalpopup')->__('Desktop Pop-up'),
          'title'     => Mage::helper('promotionalpopup')->__('Desktop Design Setup'),
          'content'   => $this->getLayout()->createBlock('promotionalpopup/adminhtml_promotionalpopup_edit_tab_lgformfactor')->toHtml(),
      ));
	  
	  $this->addTab('mdformfactor_section', array(
          'label'     => Mage::helper('promotionalpopup')->__('Tablet Pop-up'),
          'title'     => Mage::helper('promotionalpopup')->__('Tablet Design Setup'),
          'content'   => $this->getLayout()->createBlock('promotionalpopup/adminhtml_promotionalpopup_edit_tab_mdformfactor')->toHtml(),
      ));
	  
	  $this->addTab('smformfactor_section', array(
          'label'     => Mage::helper('promotionalpopup')->__('Mobile Pop-up'),
          'title'     => Mage::helper('promotionalpopup')->__('Mobile Design Setup'),
          'content'   => $this->getLayout()->createBlock('promotionalpopup/adminhtml_promotionalpopup_edit_tab_smformfactor')->toHtml(),
      ));
	  
	  
	  $this->addTab('events_section', array(
          'label'     => Mage::helper('promotionalpopup')->__('Pop-up Events'),
          'title'     => Mage::helper('promotionalpopup')->__('Pop-up Events'),
          'content'   => $this->getLayout()->createBlock('promotionalpopup/adminhtml_promotionalpopup_edit_tab_events')->toHtml(),
      ));
	  
     
      return parent::_beforeToHtml();
  }
}