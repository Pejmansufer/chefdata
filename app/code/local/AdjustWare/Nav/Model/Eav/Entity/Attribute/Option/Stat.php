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
 * @author ksenevich
 */
class AdjustWare_Nav_Model_Eav_Entity_Attribute_Option_Stat extends Mage_Core_Model_Abstract
{
    protected static $_sortedOptions;

    protected function _construct()
    {
        $this->_init('adjnav/eav_entity_attribute_option_stat');
    }

    public function addStat($optionIds)
    {
        $this->getResource()->addStat($optionIds);

        return $this;
    }

    public function recalculateStat()
    {
        $this->getResource()->recalculateStat();

        return $this;
    }

    public function getSortedOptions($attributeId)
    {
        $this->prepareSortedOptions();

        if (isset(self::$_sortedOptions[$attributeId]))
        {
            return self::$_sortedOptions[$attributeId];
        }

        return array();
    }

    public function prepareSortedOptions()
    {
        if (is_null(self::$_sortedOptions))
        {
            $featuredLimit = Mage::helper('adjnav/featured')->getFeaturedValuesLimit();
            $featuredLimitDisabled = 0 == $featuredLimit;
            $collection    = $this->getCollection()->addOrder('uses', 'DESC');

            foreach ($collection->getItems() as $stat)
            {
                if (!isset(self::$_sortedOptions[$stat->getAttributeId()]))
                {
                    self::$_sortedOptions[$stat->getAttributeId()] = array();
                }

                $count = count(self::$_sortedOptions[$stat->getAttributeId()]) < $featuredLimit;
                $end = (float)end(self::$_sortedOptions[$stat->getAttributeId()]) == $stat->getUses();

                if ($count || $end || $featuredLimitDisabled)
                {
                    self::$_sortedOptions[$stat->getAttributeId()][$stat->getOptionId()] = (float)$stat->getUses();
                }
            }
        }

        return $this;
    }
}