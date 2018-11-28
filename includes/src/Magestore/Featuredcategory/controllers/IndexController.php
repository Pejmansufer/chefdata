<?php
class Magestore_Featuredcategory_IndexController 
	extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {		
		$this->loadLayout();
        $head = $this->getLayout()->getBlock('head');
        $head->addItem('skin_css', 'css/featuredcategoryrwd.css');
		$this->renderLayout();
    }
}