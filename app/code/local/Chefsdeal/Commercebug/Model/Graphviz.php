<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
class Chefsdeal_Commercebug_Model_Graphviz
{
    public function capture()
    {    
        $collector  = new Chefsdeal_Commercebug_Model_Collectorgraphviz; 
        $o = new stdClass();
        $o->dot = Chefsdeal_Commercebug_Model_Observer_Dot::renderGraph();
        $collector->collectInformation($o);
    }
    
    public function getShim()
    {
        $shim = Chefsdeal_Commercebug_Model_Shim::getInstance();        
        return $shim;
    }    
}