<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/

class Chefsdeal_Commercebug_AjaxController extends Mage_Core_Controller_Front_Action
{   
    public function testbedAction()
    {
        $shim = $this->getShim();
        $helper = $shim->helper('configlint/runner')
        ->runLints()
        ->report();
    }
            
    public function indexAction()
    {
        $foo = new stdClass();
        $foo->message = 'Hello World';
        $this->endWithJson($foo);
    }

    public function lookupClassAction()
    {
        $this->getShim()->getModel('commercebug/crossareaajax_lookupclass')
        ->handleRequest();        
    }

    private function getClassFile($className)
    {
        if(@class_exists($className))
        {
            $r = new ReflectionClass($className);
            return $r->getFileName();		
        }
        return '';
    }

    public function lookupClassMage2Action()
    {
        $this->getShim()->getModel('commercebug/crossareaajax_lookupmage2class')
        ->handleRequest();        
    }
    
    public function lookupUriAction()
    {
        $this->getShim()->getModel('commercebug/crossareaajax_lookupuri')
        ->handleRequest();    
    }

    private function isModelContext()
    {
        if('all' == $this->getRequest()->getParam('context') || 
        'model' == $this->getRequest()->getParam('context'))
        {
            return true;		
        }
        return false;		
    }
    
    private function isHelperContext()
    {
        if('all' == $this->getRequest()->getParam('context') || 
        'helper' == $this->getRequest()->getParam('context'))
        {
            return true;		
        }
        return false;			
    }

    private function isBlockContext()
    {
        if('all' == $this->getRequest()->getParam('context') || 
        'block' == $this->getRequest()->getParam('context'))
        {
            return true;		
        }
        return false;		
    }		
    
    protected function objectToNonSemanticTable($object)
    {
        $html = '<table class="tablesorter">';
        $html .= '<thead><tr><th>Input</th><th>Class Information</th></tr></thead>';			
        $html .= '<tbody>';			
        
        $c=0;
        foreach($object as $key=>$value)
        {
            $oddeven = $c % 2 ? 'odd' : 'even';
            $html .= '<tr class="'.$oddeven.'">';
            $html .= '<td> ' . $key . '</td>';
            $value = $value ? $value : 'Could Not Find';
            $html .= '<td> ' . $value . '</td>';
            $html .= '</tr>';				
            $c++;
        }
        $html .= '</tbody>';						
        $html .= '</table>';			
        return $html;		
    }
        
    protected function endWithHtml($html)
    {
        header('Content-Type: text/html');
        echo $html;
        exit;
    }
    
    protected function endWithJson($object)
    {
        header('Content-Type: application/json');
        echo $this->getShim()->getSingleton('commercebug/jsonbroker')->jsonEncode($object);
        exit;
    }
    
    public function clearcacheAction()
    {
        $this->getShim()->getModel('commercebug/crossareaajax_clearcache')
        ->handleRequest();
    }		
     
    protected function _getSessionObject()
    {
        return $this->getShim()->getSingleton('commercebug/session');
    }
    
    public function togglehintsAction()
    {
        $this->getShim()->getModel('commercebug/crossareaajax_togglehints')
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
        
    protected function _getCacheObject()
    {
        return Mage::app()->getCache();
        return $this->getShim()->getModel('core/cache');
    }
    
	public function getShim()
	{
        $shim = Chefsdeal_Commercebug_Model_Shim::getInstance();
        return $shim;
	}	
}
