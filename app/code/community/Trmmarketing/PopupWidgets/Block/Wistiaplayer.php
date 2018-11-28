<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */
class Trmmarketing_PopupWidgets_Block_Wistiaplayer
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
		
		
		$wistiaid = $this->getData('wistiaid');
		$this->assign('wistiaid', $wistiaid);
		$videoheight = $this->getData('videoheight');
		$this->assign('videoheight', $videoheight);
		$videowidth = $this->getData('videowidth');
		$this->assign('videowidth', $videowidth);
		$videoautoplay = $this->getData('videoautoplay');
		$this->assign('videoautoplay', $videoautoplay);
		
		$toptext = $this->getData('toptext');
		$this->assign('toptext', $toptext);
		$bottomtext = $this->getData('bottomtext');
		$this->assign('bottomtext', $bottomtext);
		
        return parent::_toHtml();
    }
	
	protected function getVideoID()
	{
	
	$wistiaid = $this->getData('wistiaid');
	return $wistiaid;
		
	}

    

}
