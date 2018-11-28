<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
class Chefsdeal_Commercebug_Model_Session extends Mage_Core_Model_Session_Abstract
{
    public function __construct()
    {
        $this->init('commercebug');
    }
}