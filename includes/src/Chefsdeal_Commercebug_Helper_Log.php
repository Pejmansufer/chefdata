<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/

class Chefsdeal_Commercebug_Helper_Log
{
    public function log($message, $level=null, $file = '')
    {	  
        $shim = $this->getShim();
        if($shim->getStoreConfig('commercebug/options/should_log'))
        {
            $shim->Log($message, $level, $file);
        }	    	
    }	    
    
    public function format($thing)
    {
        $helper = $this->getShim()->helper('commercebug/formatlog_allsimple');
        if($helper)
        {
            return $helper->format($thing);
        }
        Mage::Log(sprintf('Could not instantiate helper class: %s',$alias));
    }
    
    public function getShim()
    {
        $shim = Chefsdeal_Commercebug_Model_Shim::getInstance();
        return $shim;
    }	    
}