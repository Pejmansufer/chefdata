<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/

class Chefsdeal_Commercebug_AdminajaxController extends Mage_Adminhtml_Controller_Action
{
    public function lookupClassMage2Action()
    {
        $this->getShim()->getModel('commercebug/crossareaajax_lookupmage2class')
        ->handleRequest();        
    }
    
    public function lookupClassAction()
    {
        $this->getShim()->getModel('commercebug/crossareaajax_lookupclass')
        ->handleRequest();        
    }
    
    public function togglehintsAction()
    {
        $this->getShim()->getModel('commercebug/crossareaajax_togglehints')
        ->handleRequest();
    }

    public function clearcacheAction()
    {
        $this->getShim()->getModel('commercebug/crossareaajax_clearcache')
        ->handleRequest();
    }
    
    public function lookupUriAction()
    {
        $this->getShim()->getModel('commercebug/crossareaajax_lookupuri')
        ->handleRequest();
    }
    
    public function toggleblockhintsAction()
    {
        $this->getShim()->getModel('commercebug/crossareaajax_toggleblockhints')
        ->handleRequest();
    }
    
    public function togglemageloggingAction()
    {
        $this->getShim()->getModel('commercebug/crossareaajax_togglemagelogging')
        ->handleRequest();
    }
    
    public function toggletranslationAction()
    {
        $this->getShim()->getModel('commercebug/crossareaajax_toggletranslation')
        ->handleRequest();        
    }
    
    public function togglecbloggingAction()
    {
        $this->getShim()->getModel('commercebug/crossareaajax_togglecblogging')
        ->handleRequest();
    }
    
	public function getShim()
	{
        $shim = Chefsdeal_Commercebug_Model_Shim::getInstance();
        return $shim;
	}	
    
}
