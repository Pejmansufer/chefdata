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
class AdjustWare_Nav_Model_Eav_Entity_Attribute_Stat extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('adjnav/eav_entity_attribute_stat');
    }

    public function rangeAttributes($attributes)
    {
        $sortedAttributes = array();
        $featuredLimit    = Mage::helper('adjnav/featured')->getFeaturedAttrsLimit();
        $iteration        = 0;
        $featuredLimitDisabled = 0 == $featuredLimit;

        if (Mage::helper('adjnav/featured')->isRangeAttributes())
        {
            $collection = $this->getCollection()
                ->addFieldToFilter('attribute_id', array('in' => array_keys($attributes)))
                ->addOrder('uses', 'DESC');

            foreach ($collection->getItems() as $item)
            {
                $attribute          = $attributes[$item->getAttributeId()];
                $sortedAttributes[] = $attribute;
                unset($attributes[$item->getAttributeId()]);

                if (($featuredLimit && $featuredLimit <= $iteration) && !$featuredLimitDisabled)
                {
                    $attribute->setIsOther(true);
                }

                $iteration++;
            }
        }

        foreach ($attributes as $attribute)
        {
            $sortedAttributes[] = $attribute;

            if (($featuredLimit && $featuredLimit <= $iteration) && !$featuredLimitDisabled)
            {
                $attribute->setIsOther(true);
            }
            if($attribute->getItemsCount()>0)
                $iteration++;
        }

        return $sortedAttributes;
    }
}