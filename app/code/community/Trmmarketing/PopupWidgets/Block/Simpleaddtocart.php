<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2013-2015 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */
class Trmmarketing_PopupWidgets_Block_Simpleaddtocart
    extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface
{

    /**
     * A model to serialize attributes
     * @var Varien_Object
     */
    protected $_serializer = null;

    /**
     * Initialization
     */
    protected function _construct()
    {
        $this->_serializer = new Varien_Object();
        parent::_construct();
    }

    /**
     * Produces close button links html
     *
     * @return string
     */
    protected function _toHtml()
    {
       
		$html = '';
		
		$linkbuttontext = $this->getData('linkbuttontext');
		$this->assign('linkbuttontext', $linkbuttontext);
		
		$popupproductid = $this->getData('popupproductid');
		$this->assign('popupproductid', $popupproductid);
		
		
		
        return parent::_toHtml();
    }
	
	


}
