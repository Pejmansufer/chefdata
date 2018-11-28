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
class AdjustWare_Nav_Block_Catalog_Home_View extends Mage_Core_Block_Template
{
    public function isHomePage() {
        return $this->helper('adjnav')->isHomePage();
    }
}