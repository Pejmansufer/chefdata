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
class WeaveApps_ResponsiveSlideshow_Block_Adminhtml_Responsiveslideshowgroup_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'responsiveslideshow';
        $this->_controller = 'adminhtml_responsiveslideshowgroup';

        $this->_updateButton('save', 'label', Mage::helper('responsiveslideshow')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('responsiveslideshow')->__('Delete Item'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100);

          $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText() {
        if (Mage::registry('responsiveslideshowgroup_data') && Mage::registry('responsiveslideshowgroup_data')->getId()) {
            return Mage::helper('responsiveslideshow')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('responsiveslideshowgroup_data')->getGroupName()));
        } else {
            return Mage::helper('responsiveslideshow')->__('Add Item');
        }
    }

}