<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */

class Trmmarketing_Promotionalpopup_Block_Adminhtml_Promotionalpopup_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('promotionalpopup_form', array('legend'=>Mage::helper('promotionalpopup')->__('Pop-up Settings')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('promotionalpopup')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
	  
	  
	
   $fieldset->addField('stores_id', 'multiselect', array(
           'name'      => 'stores[]',
           'label'     => Mage::helper('promotionalpopup')->__('Store View'),
           'title'     => Mage::helper('promotionalpopup')->__('Store View'),
           'required'  => true,
           'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
   ));
   
   $fieldset->addField('customergroup_ids', 'multiselect', array(
            'name'      => 'customergroups[]',
            'label'     => Mage::helper('promotionalpopup')->__('Customer Groups'),
            'title'     => Mage::helper('promotionalpopup')->__('Customer Groups'),
            'required'  => true,
            'values'    => Mage::getResourceModel('customer/group_collection')->toOptionArray()
        ));
 
	  
	  $fieldset->addField('cookie_value', 'text', array(
          'label'     => Mage::helper('promotionalpopup')->__('Unique Cookie Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'cookie_value',
      ));
	  
	  $fieldset->addField('cookie_expiry', 'select', array(
          'label'     => Mage::helper('promotionalpopup')->__('Cookie Expiry'),
          'name'      => 'cookie_expiry',
		  'class'     => 'required-entry',
          'required'  => true,
          'values'    => array(
              array(
                  'value'     => '0',
                  'label'     => Mage::helper('promotionalpopup')->__('Session'),
              ),
			  
			  array(
                  'value'     => 10,
                  'label'     => Mage::helper('promotionalpopup')->__('10 Seconds'),
              ),

              array(
                  'value'     => 100,
                  'label'     => Mage::helper('promotionalpopup')->__('100 Seconds'),
              ),
			  
			  array(
                  'value'     => 1800,
                  'label'     => Mage::helper('promotionalpopup')->__('30 minutes'),
              ),
			  
			  array(
                  'value'     => 3600,
                  'label'     => Mage::helper('promotionalpopup')->__('1 Hour'),
              ),
			  
			  array(
                  'value'     => 43200,
                  'label'     => Mage::helper('promotionalpopup')->__('12 Hours'),
              ),
			  
			  array(
                  'value'     => 86400,
                  'label'     => Mage::helper('promotionalpopup')->__('24 Hours'),
              ),
			  
			  array(
                  'value'     => 172800,
                  'label'     => Mage::helper('promotionalpopup')->__('2 Days'),
              ),
			  
			  array(
                  'value'     => 604800,
                  'label'     => Mage::helper('promotionalpopup')->__('1 Week'),
              ),
			  
			  array(
                  'value'     => 1209600,
                  'label'     => Mage::helper('promotionalpopup')->__('2 Weeks'),
              ),
			  
			  array(
                  'value'     => 2419200,
                  'label'     => Mage::helper('promotionalpopup')->__('1 Month'),
              ),
			  
			  array(
                  'value'     => 7257600,
                  'label'     => Mage::helper('promotionalpopup')->__('3 Months'),
              ),
			  
			  array(
                  'value'     => 14515200,
                  'label'     => Mage::helper('promotionalpopup')->__('6 Months'),
              ),
			  
			  array(
                  'value'     => 29030400,
                  'label'     => Mage::helper('promotionalpopup')->__('1 Year'),
              ),
			  
			  
          ),
      ));
	  
		
	$dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);	
	  
	  $fieldset->addField('begin_time', 'date', array(
		  'name' => 'begin_time',
		  'title' => Mage::helper('promotionalpopup')->__('Show Pop-up From Date'),
		  'label' => Mage::helper('promotionalpopup')->__('From date'),
		  'class'     => 'required-entry',
		  'required'  => true,
		  'image' => $this->getSkinUrl('images/grid-cal.gif'),
		  'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'       => $dateFormatIso
		));
		
		$fieldset->addField('end_time', 'date', array(
		  'name' => 'end_time',
		  'title' => Mage::helper('promotionalpopup')->__('Show Pop-up Until Date'),
		  'label' => Mage::helper('promotionalpopup')->__('Until date'),
		  'class'     => 'required-entry',
		  'required'  => true,
		  'image' => $this->getSkinUrl('images/grid-cal.gif'),
		  'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'       => $dateFormatIso
		));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('promotionalpopup')->__('Open Automatically'),
          'name'      => 'status',
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
	  
	 $fieldset->addField('sort_order', 'text', array(
          'label'     => Mage::helper('promotionalpopup')->__('Sort Order'),
          'required'  => false,
          'name'      => 'sort_order',
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