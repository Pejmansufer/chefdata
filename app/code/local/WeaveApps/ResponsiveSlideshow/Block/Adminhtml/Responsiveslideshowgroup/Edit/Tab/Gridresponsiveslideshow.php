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
class WeaveApps_ResponsiveSlideshow_Block_Adminhtml_Responsiveslideshowgroup_Edit_Tab_Gridresponsiveslideshow extends Mage_Adminhtml_Block_Widget_Container {

    /**
     * Set template
     */
    public function __construct() {
        parent::__construct();
        $this->setTemplate('wa_responsiveslideshow/responsiveslideshow.phtml');
    }

    public function getTabsHtml() {
        return $this->getChildHtml('tabs');
    }

    /**
     * Prepare button and grid
     *
     */
    protected function _prepareLayout() {
        $this->setChild('tabs', $this->getLayout()->createBlock('responsiveslideshow/adminhtml_responsiveslideshowgroup_edit_tab_responsiveslideshow', 'responsiveslideshowgroup.grid.responsiveslideshow'));
        return parent::_prepareLayout();
    }

    public function getresponsiveslideshowgroupData() {
        return Mage::registry('responsiveslideshowgroup_data');
    }

    public function getresponsiveslideshowsJson() {
        $responsiveslideshows = explode(',', $this->getresponsiveslideshowgroupData()->getBannerIds());
        if (!empty($responsiveslideshows) && isset($responsiveslideshows[0]) && $responsiveslideshows[0] != '') {
            $data = array();
            foreach ($responsiveslideshows as $element) {
                $data[$element] = $element;
            }
            return Zend_Json::encode($data);
        }
        return '{}';
    }

}
