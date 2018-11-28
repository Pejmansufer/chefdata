<?php
class Extendware_EWQuickView_Block_Dialog_Error extends Extendware_EWQuickView_Block_Dialog_Abstract
{
	protected $messages = array();
	
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('extendware/ewquickview/dialog/error.phtml');
    }
    
	public function getMessages() {
 		return $this->messages;
 	}
 	
	public function setMessages(array $messages = array()) {
 		return $this->messages = $messages;
 	}
 	
 	public function addMessage($data) {
 		$this->messages[] = $data;
 		return $this;
 	}
 	
	protected function _toHtml()
    {
        $html = parent::_toHtml();
 		if ($this->getWrapInJs() === true) {
 			$html = Mage::helper('core/js')->getScript('ewquickview.open(' . json_encode($html) . ', ' . json_encode($this->__('Error encountered')) . ');');
 		}
 		
 		return $html;
    }
}