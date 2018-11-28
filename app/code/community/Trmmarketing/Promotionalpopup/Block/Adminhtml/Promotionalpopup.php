<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */

class Trmmarketing_Promotionalpopup_Block_Adminhtml_Promotionalpopup extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_promotionalpopup';
    $this->_blockGroup = 'promotionalpopup';
    $this->_headerText = Mage::helper('promotionalpopup')->__('Pop-up Manager');
    $this->_addButtonLabel = Mage::helper('promotionalpopup')->__('Create New Pop-up');
    parent::__construct();
  }
}