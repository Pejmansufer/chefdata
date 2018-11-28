<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
class Chefsdeal_Commercebug_Model_Crossareaajax_Lookupclass extends Chefsdeal_Commercebug_Model_Crossareaajax
{
    public function handleRequest()
    {
        $shim = $this->getShim();
        $helper = $shim->helper('commercebug/classurilookup');
        $class = trim($this->getRequest()->getParam('class'));
        $uri 	= $helper->classToUri($class);
        $type 	= $helper->classToType($class);
        
        $response = new stdClass();
        $response->{'Resolves to Alias '} = $uri;
        $response->{'Alias [' . $uri . '] in ' . $type . ' context resolves to class'} = 
        $class = Mage::getConfig()->getGroupedClassName($type,$uri);
        $file  = $this->getClassFile($class);
        if($file)
        {
            $class = $class . 
            '<br>' . 
            '<span class="pathinfo"> ' . $file . ' </path>';			
        }
        $response->{'Alias [' . $uri . '] in ' . $type . ' context resolves to class'} = $class;
        
        $html = $this->objectToNonSemanticTable($response);
        $html = str_replace('<dd','<dd class="classname"',$html);
        
        $this->endWithHtml($html);

    }
}