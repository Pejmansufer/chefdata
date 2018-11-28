<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
class Chefsdeal_Commercebug_Model_Crossareaajax_Lookupmage2class extends Chefsdeal_Commercebug_Model_Crossareaajax
{   
    public function handleRequest()
    {                      
        $o = $this->getShim()->getObjectManager();
        $factory = $this->_getProtectedObjectProperty($o, '_factory');
        
        $config         = $this->_getProtectedObjectProperty($factory, '_config');        
        $om_config      = $this->_getProtectedObjectProperty($config,  '_omConfig');
        $preferences    = $this->_getProtectedObjectProperty($om_config,  '_preferences');

        $alias          = $this->getRequest()->getParam('class');
        $alias          = preg_replace('%[^\a-z0-9_]i%','',$alias);
        
        $real_class = array_key_exists($alias, $preferences) ? $preferences[$alias] : $alias;
        
        $response       = new stdClass;
        $response->{$alias} = $real_class . '<br/>' . '<code>' . 
        $this->getClassFile($real_class) . '</code>';
        
        $html = $this->objectToNonSemanticTable($response);
        $this->endWithHtml($html);
    }
    
    private function _getProtectedObjectProperty($object, $prop_name)
    {
        $r = new ReflectionClass($object);
        $prop = $r->getProperty($prop_name);
        $prop->setAccessible(true);        
        return $prop->getValue($object);    
    }
}