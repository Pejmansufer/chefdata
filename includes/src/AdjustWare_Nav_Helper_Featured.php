<?php
/**
 * Layered Navigation Pro
 *
 * @category:    AdjustWare
 * @package:     AdjustWare_Nav
 * @version      2.5.11
 * @license:     HxObcXe62uQsQGs98TzFPLxwiM3aCakojlW7nJdmvI
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
/**
 * 
 * @author ksenevich
 */
class AdjustWare_Nav_Helper_Featured extends Mage_Core_Helper_Abstract
{
    public function isAutoRange()
    {
        return (Mage::getStoreConfig('design/adjnav_featured/collect_period') > 0);
    }

    public function collectPeriod()
    {
        return (int)Mage::getStoreConfig('design/adjnav_featured/collect_period');
    }

    public function getFeaturedAttrsLimit()
    {
        return (int)Mage::getStoreConfig('design/adjnav_featured/featured_attrs_limit');
    }

    public function getFeaturedValuesLimit()
    {
        return (int)Mage::getStoreConfig('design/adjnav_featured/featured_vals_limit');
    }

    public function isRangeAttributes()
    {
        return ($this->isAutoRange() && Mage::getStoreConfig('design/adjnav_featured/use_ranges_attr'));
    }

    public function isRangeValues()
    {
        return ($this->isAutoRange() && Mage::getStoreConfig('design/adjnav_featured/use_ranges_val'));
    }
}