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
class WeaveApps_ResponsiveSlideshow_Model_ResponsiveSlideshowgroup extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('responsiveslideshow/responsiveslideshowgroup');
    }
    
    public function getDataByGroupCode($groupid) {        
        $groupData = array();
        $responsiveslideshowData = array();
        $result = array('group_data'=>$groupData,'responsiveslideshow_data'=>$responsiveslideshowData);
        $collection = Mage::getResourceModel('responsiveslideshow/responsiveslideshowgroup_collection');
        $collection->getSelect()->where('group_id = ?', $groupid)->where('status = 1');
        foreach ($collection as $record) {
            $responsiveslideshowIds = $record->getBannerIds();
             //$sortOder = $record->getSortOder();

            $responsiveslideshowModel = Mage::getModel('responsiveslideshow/responsiveslideshow');
            $responsiveslideshowData = $responsiveslideshowModel->getDataByBannerIds($responsiveslideshowIds);
            $result = array('group_data' => $record, 'responsiveslideshow_data' => $responsiveslideshowData);
        }
        return $result;
    }

}