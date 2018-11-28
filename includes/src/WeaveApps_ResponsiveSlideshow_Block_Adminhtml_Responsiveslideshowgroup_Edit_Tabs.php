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
class WeaveApps_ResponsiveSlideshow_Block_Adminhtml_Responsiveslideshowgroup_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('responsiveslideshowgroup_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('responsiveslideshow')->__('Responsive Slideshow Group Information'));
    }

    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label' => Mage::helper('responsiveslideshow')->__('General Information'),
            'alt' => Mage::helper('responsiveslideshow')->__('responsiveslideshow Group'),
            'content' => $this->getLayout()->createBlock('responsiveslideshow/adminhtml_responsiveslideshowgroup_edit_tab_form')->toHtml(),
        ));

        $this->addTab('grid_section', array(
            'label' => Mage::helper('responsiveslideshow')->__('Slides'),
            'alt' => Mage::helper('responsiveslideshow')->__('responsiveslideshows'),
            'content' => $this->getLayout()->createBlock('responsiveslideshow/adminhtml_responsiveslideshowgroup_edit_tab_gridresponsiveslideshow')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}