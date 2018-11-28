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
class AdjustWare_Nav_Block_Catalog_Layer_Params extends AdjustWare_Nav_Block_Catalog_Layer_View
{
    public function getStateInfo()
    {
       return parent::getStateInfo();
    }

    protected function _prepareLayout()
    {
        return $this;
    }
	
	protected function _beforeToHtml()
    {
        return $this;
    }
}