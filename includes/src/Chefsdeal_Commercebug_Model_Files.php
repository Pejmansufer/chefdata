<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
class Chefsdeal_Commercebug_Model_Files
{
    public function capture()
    {
        $collector  = new Chefsdeal_Commercebug_Model_Collectorfiles; 
        $o = new stdClass();
        $o->information = get_included_files();

        //selfishly do this in a few steps so symlinked module folders are also removed
        //and not placed into the "other" folder
        $o->information = preg_replace('%^.+?/app%','app', $o->information);        
        $o->information = str_replace($this->getShim()->getBaseDir().'/','',$o->information);
        $o->otherInformation = new stdClass();
        $o->otherInformation->baseDir = $this->getBaseDir();
        $collector->collectInformation($o);
    }
    
    public function getBaseDir($type=false)
    {
        return $this->getShim()->getBaseDir($type);        
    }
    
    public function getShim()
    {
        $shim = Chefsdeal_Commercebug_Model_Shim::getInstance();
        return $shim;	    
    }    
}