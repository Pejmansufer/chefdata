<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */

class Trmmarketing_Promotionalpopup_Block_Adminhtml_Promotionalpopup_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'promotionalpopup';
        $this->_controller = 'adminhtml_promotionalpopup';
        
        $this->_updateButton('save', 'label', Mage::helper('promotionalpopup')->__('Save Pop-up'));
        $this->_updateButton('delete', 'label', Mage::helper('promotionalpopup')->__('Delete Pop-up'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('promotionalpopup_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'promotionalpopup_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'promotionalpopup_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('promotionalpopup_data') && Mage::registry('promotionalpopup_data')->getId() ) {
            return Mage::helper('promotionalpopup')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('promotionalpopup_data')->getTitle()));
        } else {
            return Mage::helper('promotionalpopup')->__('Add Item');
        }
    }
}