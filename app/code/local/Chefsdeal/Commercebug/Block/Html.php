<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/

class Chefsdeal_Commercebug_Block_Html extends Mage_Core_Block_Template
{	
    protected $_config;
    protected function _checkValidScriptPath($scriptPath)
    {
        $paths_to_check = array(Mage::getBaseDir('design'),Mage::getModuleDir('', 'Chefsdeal_Commercebug'));
        $valid			= false;
        foreach($paths_to_check as $path)
        {
            if(strpos($scriptPath, realpath($path)) === 0)
            {
                $valid = true;
            }
        }
        return $valid;		
    }
    
    public function setScriptPath($dir)
    {
        $scriptPath = realpath($dir);
        if ($this->_checkValidScriptPath($scriptPath)) {
            $this->_viewDir = $dir;
        } else {
            Mage::log('Not valid script path:' . $dir, Zend_Log::CRIT, null, null, true);
        }
        return $this;
    }

    public function fetchView($fileName)			    
    {	    	
        $module_dir = Mage::getModuleDir('', 'Chefsdeal_Commercebug');
        $this->setScriptPath($module_dir . '/static');
        return parent::fetchView($this->getTemplate());
    }
    
    const PATH_FRONTEND_COMMERCEBUG = 'commercebug';
    public function getPathSkin()
    {       
        $shim = $this->getShim();
        if(!$shim->isMage2())
        {	    	    
            $url = Mage::getBaseUrl('js') . 'commercebug';
        }
        else
        {
            #Chefsdeal_Commercebug_Model_Shim::Log('Cleanup in ' . __CLASS__ . ': ' . __LINE__ );
                
            $dir = $shim->getObjectManager()->get('Magento\App\Dir');
            $url = '/' . $dir->getUri(\Magento\App\Dir::STATIC_VIEW) . '/' .
            self::PATH_FRONTEND_COMMERCEBUG;            
           
        }        
        return $url;
    }
 
    const UPDATE_URL = 'http://commercebug.alanstorm.com/index.php/version?callback=?';
    public function getUpdateUrl()
    {
        return self::UPDATE_URL;
    }	 		 	


    //86400 = 1 day	 	
    const UPDATE_CHECK_RATE_IN_SECONDS = 86400;
    public function getCheckForUpdatesFlag()
    {			
        return false;
        $shim = $this->getShim();
        $last_time = $shim->getSingleton('commercebug/jsonbroker')->jsonDecode(Mage::getStoreConfig('commercebug/options/update_last_check'));
        $last_time = $last_time->date;	 			
        if((strToTime($last_time) + self::UPDATE_CHECK_RATE_IN_SECONDS) > time())
        {
            return false;
        }	 		
        
        //if we're still here, check teh config flag
        return Mage::getStoreConfig('commercebug/options/check_for_updates');
    }

    public function fetchUseKeyboardShortcuts()
    {
        return Mage::getStoreConfig('commercebug/options/keyboard_shortcuts');		
    }
    public function calculateNextUpdateCheck()
    {
        $last_time = $this->getShim()->getModel('commercebug/jsonbroker')->jsonDecode(Mage::getStoreConfig('commercebug/options/update_last_check'));		
        return date(DATE_RFC822, strToTime($last_time->date)+self::UPDATE_CHECK_RATE_IN_SECONDS);
    }
    public function getLastUpdateCheck()
    {
        $last_time = $this->getShim()->getModel('commercebug/jsonbroker')->jsonDecode(Mage::getStoreConfig('commercebug/options/update_last_check'));		
        return date(DATE_RFC822, strToTime($last_time->date));		
    }
    public function resetLastUpdated()
    {
        $object				= new StdClass();
        $object->date		= date(DATE_RFC822);	 		
        
        $groups_value 		= array();			
        $groups_value['options']['fields']['update_last_check']['value'] = $this->getShim()->getModel('commercebug/jsonbroker')->jsonEncode($object);
        $model = $this->getShim()->getModel('adminhtml/config_data')
            ->setSection('commercebug')
            ->setWebsite(null)
            ->setStore(null)
            ->setGroups($groups_value)
            ->save();
            
        Mage::getConfig()->reinit();
        Mage::app()->reinitStores();				
    }
    
    public function fetchResultsFromLastUpdateCheck()
    {
        return Mage::getStoreConfig('commercebug/options/update_last_check');	 	
    }
    
    public function includeStatic($path)
    {	 		
        ob_start();
        include('app/code/local/Alanstormdotcom/Commercebug/static/' . $path);
        return ob_get_clean();
    }
    
    public function getLayout()
    {
        $shim = $this->getShim();
        return $shim->getSingleton('core/layout');
    }
    
    function getShim()
    {
        $shim = Chefsdeal_Commercebug_Model_Shim::getInstance();
        return $shim;		
    }        
    
    public function getTabIdPairs()
    {
        $tab_id_pairs = array();
        if(!$this->getConfig())
        {
            return $tab_id_pairs;
        }
        
        if($this->getShim()->isMage2())
        {
            $parts = $this->getConfig()->xpath('tabs');
            $part  = array_shift($parts);
            $parts = $part->children();
        }
        else
        {
            $parts = $this->getConfig()->xpath('*');
        }
        
        foreach($parts as $node)
        {
            $tab_id_pairs[$node->getName()] = (string) $node->title;
        }		
        return $tab_id_pairs;
    }
    
    public function getTabIdAndHtmlPairs()
    {			
        $tab_html_pairs = array();
        if(!$this->getConfig())
        {
            return $tab_html_pairs;
        }
        foreach($this->getConfig()->xpath('*') as $node)
        {
            $tab_html_pairs[$node->getName()] = $this->getShim()->createBlock(
            (string) $node->block
            )
            ->setConfigNode($node)
            ->toHtml();
        }		
        return $tab_html_pairs;
    }

    protected function getConfig()
    {
        $shim = $this->getShim();
        if($shim->isMage2())
        {
            #Chefsdeal_Commercebug_Model_Shim::Log('Mage 2 Cleanup at: ' . __CLASS__ . ' ' . __LINE__);
            $this->_config = simplexml_load_file(realpath(dirname(__FILE__).'/../etc/commercebug.xml'));
                        
            return $this->_config;
        }
        

        $config = Mage::getConfig()->loadModulesConfiguration('commercebug.xml');
        if(!$this->_config)
        {
            $this->_config = Mage::getConfig()->loadModulesConfiguration('commercebug.xml')->getNode('tabs');
        }
        
        return $this->_config;
    }
    
    protected function _renderJsonAssignment($var_name, $object)
    {
        if(!is_object($object))
        {
            throw new Exception("Attempted to add non object as json");
        }
        
        $var_name = strToLower(preg_replace('%[^a-z_]%i','',$var_name));
        return 'var ' . $var_name . ' = ' . $this->getShim()->getSingleton('commercebug/jsonbroker')->jsonEncode($object);
    }    
}