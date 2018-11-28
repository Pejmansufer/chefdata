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
class WeaveApps_ResponsiveSlideshow_Model_ResponsiveSlideshow extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('responsiveslideshow/responsiveslideshow');
    }

    public function getAllAvailBannerIds(){
        $collection = Mage::getResourceModel('responsiveslideshow/responsiveslideshow_collection')->getAllIds();
        return $collection;
    }

    public function getAllBanners() {
        $collection = Mage::getResourceModel('responsiveslideshow/responsiveslideshow_collection');
        $collection->getSelect()->where('status = ?', 1);
        $data = array();
        foreach ($collection as $record) {
            $data[$record->getId()] = array('value' => $record->getId(), 'label' => $record->getfilename());
        }
        return $data;
    }
    public function getDataByBannerIds($bannerIds) {
        $data = array();
        if ($bannerIds != '') {
            $collection = Mage::getResourceModel('responsiveslideshow/responsiveslideshow_collection');
            $collection->getSelect()->where('rs_id IN (' . $bannerIds . ')')->order('sort_order');
            $collection->getSelect()->where('rs_id IN (' . $bannerIds . ')');
            foreach ($collection as $record) {
                $status = $record->getStatus();
                if ($status == 1) {
                    $data[] = $record;
                }
            }
        }
        return $data;
    }
}