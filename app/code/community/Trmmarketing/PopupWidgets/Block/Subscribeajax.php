<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */
class Trmmarketing_PopupWidgets_Block_Subscribeajax
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

    
    protected function _toHtml()
    {
       
		$html = '';
		$submitbuttonlabel = $this->getData('submitbuttonlabel');
		$this->assign('submitbuttonlabel', $submitbuttonlabel);
		$emaildefaulttext = $this->getData('emaildefaulttext');
		$this->assign('emaildefaulttext', $emaildefaulttext);
		
		$emailsuccesstext = $this->getData('emailsuccesstext');
		$this->assign('emailsuccesstext', $emailsuccesstext);
		$autoclosesuccess = $this->getData('autoclosesuccess');
		$this->assign('autoclosesuccess', $autoclosesuccess);
		
		$tracksubconversion = $this->getData('tracksubconversion');
		$this->assign('tracksubconversion', $tracksubconversion);
		$conversionsuccesslabel = $this->getData('conversionsuccesslabel');
		$this->assign('conversionsuccesslabel', $conversionsuccesslabel);
		$subsuccessexpiry = $this->getData('subsuccessexpiry');
		$this->assign('subsuccessexpiry', $subsuccessexpiry);
		
		$sendemail = $this->getData('sendemail');
		$this->assign('sendemail', $sendemail);
		$couponid = $this->getData('couponid');
		$this->assign('couponid', $couponid);
		$couponlength = $this->getData('couponlength');
		$this->assign('couponlength', $couponlength);
		$emailtemplate = $this->getData('emailtemplate');
		$this->assign('emailtemplate', $emailtemplate);
		
		
		
		
        return parent::_toHtml();
    }
	
	


}
