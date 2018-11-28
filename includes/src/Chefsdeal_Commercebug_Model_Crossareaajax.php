<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
/**
* A helper to help manage the "session aware ajax actions
* a are needed for both the frontend and adminarea" problem
*/
abstract class Chefsdeal_Commercebug_Model_Crossareaajax
{
    static protected $_actionNames=array('togglehints',
    'lookupUri','lookupClass','clearcache',
    'toggleblockhints','togglemagelogging','togglecblogging',
    'toggletranslation','lookupClassMage2');
    
    abstract function handleRequest();

    public function getLayout()
    {
        return Mage::getSingleton('core/layout');
    }

    protected function classDecoratedWithPath($class)
    {
        $file  = $this->getClassFile($class);
        if($file)
        {
            $class = $class . 
            '<br>' . 
            '<span class="pathinfo">' . $file . '</path>';			
        }		
        return $class;
    }

    protected function objectToDefinitionList($object, $attributes=array())
    {
        $html = '<dl';
        foreach($attributes as $key=>$value)
        {
            $html .= ' ' . $key . '="' . $value .'"';
        }
        $html.='>';
        foreach($object as $key=>$value)
        {
            $html .= '<dt> ' . $key . '</dt>';
            $value = $value ? $value : 'Could Not Find';
            $html .= '<dd> ' . $value . '</dd>';
        }
        $html .= '</dl>';
        
        return $html;
    }
    
    protected function isBlockContext()
    {
        if('all' == $this->getRequest()->getParam('context') || 
        'block' == $this->getRequest()->getParam('context'))
        {
            return true;		
        }
        return false;		
    }	
    
    protected function isHelperContext()
    {
        if('all' == $this->getRequest()->getParam('context') || 
        'helper' == $this->getRequest()->getParam('context'))
        {
            return true;		
        }
        return false;			
    }
    
    protected function isModelContext()
    {
        if('all' == $this->getRequest()->getParam('context') || 
        'model' == $this->getRequest()->getParam('context'))
        {
            return true;		
        }
        return false;		
    }
    
    protected function _getSessionObject()
    {
        return $this->getShim()->getSingleton('commercebug/session');
    }
    
    public function getShim()
    {
        $shim = Chefsdeal_Commercebug_Model_Shim::getInstance();
        return $shim;		
    }
    
    public function getRequest()
    {
        return $this->getShim()->getApp()->getRequest();
    }
    
    protected function getClassFile($className)
    {
        if(@class_exists($className))
        {
            $r = new ReflectionClass($className);
            return $r->getFileName();		
        }
        return '';
    }

    protected function objectToNonSemanticTable($object)
    {
        $html = '<table class="tablesorter">';
        $html .= '<thead><tr><th>Label</th><th>Class Information</th></tr></thead>';			
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
    
    static public function getAjaxActionNames()
    {
        return self::$_actionNames;
    }

    static public function appendAction($string)
    {
        return $string . 'Action';
    }
        
    /**
    * Called when developer mode is on to make sure we keep controllers in sync
    */    
    static public function validateControllers()
    {
        $controllers = array('Chefsdeal_Commercebug_AjaxController','Chefsdeal_Commercebug_AdminajaxController');
        
        foreach($controllers as $controller)
        {
            require_once self::_controllerNameToPath($controller);
            $methods = get_class_methods($controller);
            $actions = array_map(array(
            'Chefsdeal_Commercebug_Model_Crossareaajax','appendAction'), self::$_actionNames);
            $diff    = array_diff($actions,$methods);            
            if(count($diff) > 0)
            {
                throw new Exception(sprintf(
                'Commerce Bug Error: The controller %s is missing the methods %s',
                $controller, implode(',',$diff)));
            }            
        }    
    }
    
    static protected function _controllerNameToPath($string)
    {
        $parts = explode("_", $string);
        $name = array_shift($parts) . '/' . array_shift($parts) .
        '/controllers/' . implode("/" , $parts) . '.php';        
        return $name;
        
    }
}