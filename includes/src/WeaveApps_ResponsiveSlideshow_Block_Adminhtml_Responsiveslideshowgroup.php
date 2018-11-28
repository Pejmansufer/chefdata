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
class WeaveApps_ResponsiveSlideshow_Block_Adminhtml_Responsiveslideshowgroup extends Mage_Adminhtml_Block_Widget_Grid_Container {
    public function __construct() {
        $this->_controller = 'adminhtml_responsiveslideshowgroup';
        $this->_blockGroup = 'responsiveslideshow';
        $this->_headerText = Mage::helper('responsiveslideshow')->__('Responsive Slideshow Group Manager');
        $this->_addButtonLabel = Mage::helper('responsiveslideshow')->__('Add responsive slideshow group');
        parent::__construct();
    }

}