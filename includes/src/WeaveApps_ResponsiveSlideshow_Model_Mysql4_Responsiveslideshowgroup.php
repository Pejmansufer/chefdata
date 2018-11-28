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
class WeaveApps_ResponsiveSlideshow_Model_Mysql4_ResponsiveSlideshowgroup extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        $this->_init('responsiveslideshow/responsiveslideshowgroup', 'group_id');
    }
}