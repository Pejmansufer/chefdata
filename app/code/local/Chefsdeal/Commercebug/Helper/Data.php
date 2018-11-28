<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/

class Chefsdeal_Commercebug_Helper_Data extends Chefsdeal_Commercebug_Helper_Abstract
{	
    public function calculateRealModuleName()
    {
        return self::_getModuleName();
    }
    
    
    public function __()
    {
        $args = func_get_args();        
        if(!$this->isAllowed())
        {            
            return array_shift($args);            
        }        
        return parent::__(array_shift($args));        
    }

    /**
    * Inline translation screw up commerce bug's javascript
    */    
    public function isAllowed()
    {    
        return !$this->getShim()->getSingleton('core/translate_inline')->isAllowed();
    }    
}