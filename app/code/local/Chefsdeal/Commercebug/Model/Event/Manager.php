<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
class Chefsdeal_Commercebug_Model_Event_Manager extends Mage_Core_Model_Event_Manager
{
    protected $_commerceBugCollector;
    protected $_commerceBugCollectedEvents;
    public function dispatch($eventName, array $data = array())
    {        
        $this->_commerceBugCollectedEvents[] = $eventName;
        return parent::dispatch($eventName, $data);
    }
    
    public function getCommerceBugCollector()
    {
        if(!$this->_commerceBugCollector)
        {
            $this->_commerceBugCollector = new Chefsdeal_Commercebug_Model_Collectorevents;            
        }
        return $this->_commerceBugCollector;
    }
    
    public function collectAllMage2()
    {
        foreach(array_unique($this->_commerceBugCollectedEvents) as $eventName)
        {
            $o        = new stdClass();
            $o->area  = 'unavailable';
            $o->event = $eventName;
            $o->data  = array();
            $this->getCommerceBugCollector()->collectInformation($o);        
        }        
        $this->_commerceBugCollectedEvents = array();
    }
}