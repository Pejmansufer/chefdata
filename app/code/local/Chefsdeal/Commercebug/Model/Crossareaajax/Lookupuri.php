<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
class Chefsdeal_Commercebug_Model_Crossareaajax_Lookupuri extends Chefsdeal_Commercebug_Model_Crossareaajax
{
    public function handleRequest()
    {
        $shim = $this->getShim();
        $response = new stdClass();
        $uri = trim($this->getRequest()->getParam('uri'));
        
        //need to surpress "could not include" warning
        
        if($this->isModelContext())
        {
            $response->{'Model Grouped Class Name '} 				 = $shim->getGroupedClassName('model',$uri);
            $response->{"Mage::getModel('$uri') creates a "} 		 = @get_class(Mage::getModel($uri));			
            $response->{"Mage::getResourceModel('$uri') creates a "} = @get_class(Mage::getResourceModel($uri));
        }


        if($this->isHelperContext())
        {
            $response->{'Helper Grouped Class Name '} 	= $shim->getGroupedClassName('helper',$uri);			
            $test = @class_exists($response->{'Helper Grouped Class Name '});
            if($test)
            {
                $response->{'Mage::helper(\''.$uri.'\') creates a '} = @get_class(Mage::helper($uri));
            }
            else
            {
                $response->{'Mage::helper(\''.$uri.'\') creates a '} = false;
            }
        }
        
        if($this->isBlockContext())
        {                
            $response->{"Block Grouped Class Name"}		= $shim->getGroupedClassName('block',$uri);			
            $response->{"\$this->getLayout()->createBlock('$uri') creates a "} 	= @get_class($this->getLayout()->createBlock($uri));
        }			
        
        
        //decorate response with classname
        foreach($response as $key=>$value)
        {
            if($value)
            {
                $response->{$key} = $this->classDecoratedWithPath($value);
            }
        }


        //$response->block 			= get_class(Mage::getBlock($uri));
        
        $html = $this->objectToDefinitionList($response);
        $html = $this->objectToNonSemanticTable($response);
        $html = str_replace('<dd','<dd class="classname"',$html);
        
        $this->endWithHtml($html);    
    }
}