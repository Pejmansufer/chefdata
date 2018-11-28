<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
class Chefsdeal_Commercebug_Model_Shim
{
    static protected $_instance;
    protected $_globalConfig=false;    
    protected $_dirNamespaceProtection=false;
    
    
    public function createBlock($type, $name='', array $attributes = array())
    {
        $shim = self::getInstance();
        $layout = $shim->getSingleton('core/layout');

        $is_mage_2 = $this->_isMage2();
        if($is_mage_2)
        {            
            $type = $this->_aliasToDefaultClass($type, 'Block');
            return $layout->createBlock($type, $name, $attributes);
        }
        else
        {
            return $layout->createBlock($type, $name, $attributes);
        }        
    }
    
    public function helper($class)
    {
        $is_mage_2 = $this->_isMage2();
        if($is_mage_2)
        {        
            $class = $this->_aliasToDefaultClass($class, 'Helper');            
            return $this->getObjectManager()->get($class);
        }
        else
        {
            #var_dump($class);
            return Mage::helper($class);
        }
    
    }

    public function getLayout()
    {
        if(!$this->isMage2())
        {
            return Mage::getSingleton('core/layout');
        }
        
        return $this->getObjectManager()->get('Magento\View\LayoutInterface');
    }
    
    public function getFullActionName($controller)
    {
        if(!$this->isMage2())
        {
            return $controller->getFullActionName();
        }
        return $this->getApp()->getRequest()->getFullActionName();
    }
    
    public function getApp()
    {
        if(!$this->isMage2())
        {
            return Mage::app();
        }
        $app = $this->getObjectManager()
        ->get('\Magento\Core\Model\App');
        return $app;
    }
    
    public function getModel($modelClass = '', $arguments = array())
    {
        $is_mage_2 = $this->_isMage2();
        if($is_mage_2)
        {
            $class = $this->_aliasToDefaultClass($modelClass);
            return $this->getObjectManager()->create($class);
        }
        else
        {
            return Mage::getModel($modelClass, $arguments);
        }
    }
    
    public function getSingleton($modelClass = '', $arguments = array())
    {
        $is_mage_2 = $this->_isMage2();
        if($is_mage_2)
        {
            $class = $this->_aliasToDefaultClass($modelClass);
            return $this->getObjectManager()->get($class);
        }
        else
        {
            return Mage::getSingleton($modelClass, $arguments);
        }
    }
    
    public function getObjectManager()
    {
        $dir = $this->_getDirForNamepsaceProtection();        
        return include $dir . '/getObjectManager.php';
    }    
    
    /**
    * Bit of a hack as part of our "hide namespaces from Magento 1"
    */    
    protected function _getDirForNamepsaceProtection()
    {
        if(!$this->_dirNamespaceProtection)
        {
            $this->_dirNamespaceProtection = realpath(dirname(__FILE__) . '/../namespace-protection');
        }
        return $this->_dirNamespaceProtection;
    }
    
    protected function _getNamespaceForGroup($group)
    {
        switch($group)
        {
            case 'commercebug':
                $module = 'Chefsdeal_Commercebug';
                $parts  = explode('_', $module);
                $namespace = array_shift($parts);
                return $namespace;
            default:
                return 'Magento';
        }
        
    }
    
    public function getGlobalConfig()
    {
        if(!$this->_globalConfig)
        {
            $this->_globalConfig = $this->getObjectManager()
                ->create('Magento\Core\Model\Store\Config');
        }
        return $this->_globalConfig;     
    }
    
    public function getStoreConfig($path)
    {
        if(!$this->isMage2())
        {
            return Mage::getStoreConfig($path);
        }
        
        return $this->getGlobalConfig()->getConfig($path);
    }
    
    public function getStoreConfigFlag($path)
    {
        $config = $this->getStoreConfig($path);
        $flag   = strToLower($config);        
        return (boolean) !empty($flag) && $flag !== false;
    }
    
    protected function _aliasToDefaultClass($string,$context='Model')
    {
        $full_module_name = 'Chefsdeal_Commercebug';
        list($package_name, $module_name) = explode('_', $full_module_name);
        if(strpos($string, $full_module_name) === 0)
        {
            return $string;
        }
        
        if($context == 'Helper' && strpos($string, '/') === false)
        {
            $string .= '/data';
        }    
                
        list($group, $class) = explode('/',$string);    
        
        $class = str_replace(' ','_',ucwords(str_replace('_',' ',$class)));
        $namespace = $this->_getNamespaceForGroup($group);
        if(strpos($namespace, $package_name) === 0)
        {
            $class = $namespace . '_' . ucwords($group) . '_' . $context . '_' . $class;
        }    
        else
        {
            $class = str_replace('_','\\',$class);
            $class = $namespace . '\\' . ucwords($group) . '\\' . $context . '\\' . $class;
        }
        return $class;
    }
    
    protected function _isMage2()
    {        
        return !class_exists('Mage');
        $is_mage_1 = version_compare('1.99',Mage::getVersion(),'>');      
        return !$is_mage_1;
    }    

    public function isMage2()
    {
        return $this->_isMage2();
    }
    
    static public function getInstance()
    {
        if(!self::$_instance)
        {
            self::$_instance = new Chefsdeal_Commercebug_Model_Shim;
        }
        
        return self::$_instance;
    }
    
    public function getProtectedPropertyFromObject($property, $object)
    {
        return $this->_getProtectedPropertyFromObject($property, $object);
    }
    
    protected function _getProtectedPropertyFromObject($property, $object)
    {
        $r = new ReflectionClass($object);
        $p = $r->getProperty($property);
        $p->setAccessible(true);            
        return $p->getValue($object);    
    }
    
    public function getGroupedClassName($groupType, $classId, $groupRootNode=null)
    {
        if($this->isMage2())
        {
            throw new Exception("Unsupported code path");
        }
        else
        {
            return Mage::getConfig()->getGroupedClassName($groupType, $classId, $groupRootNode);
        }
    }

    public function getStore()
    {
        if($this->isMage2())
        {
            return $this->getObjectManager()
            ->get('Magento\Core\Model\StoreManager')
            ->getStore();
        }
        return Mage::app()->getStore();
    }
    public function getRequest()
    {
        if(!$this->isMage2())
        {
            return Mage::app()->getRequest();
        }

        $om     = $this->getObjectManager();
        $app    = $om->get('Magento\Core\Model\App');
        return $app->getRequest();
    }

    public function cleanCache()
    {
        if(!$this->isMage2())
        {
            Mage::app()->cleanCache();
            return;
        }
        $this->getApp()->cleanCache();
    }
    
    static public function log($string)
    {
        Mage::Log($string);
    }
    
    public function getBaseDir($type=false)
    {
        if(!$this->isMage2() && $type)
        {        
            return Mage::getBaseDir($type);
        }
        else if(!$this->isMage2())
        {
            return Mage::getBaseDir();
        }
        
        $dir = $this->_getDirForNamepsaceProtection();        
        return include $dir . '/getBaseDir.php';
    }    
    
    public function setConfigForRequest($config_path, $value)
    {
        if(!$this->isMage2())
        {
            $store  = Mage::app()->getStore();
            $code   = $store->getCode();             
            $config_path = "stores/$code/" . $config_path;
            return Mage::getConfig()->setNode($config_path, $value);            
        }
        $store  = $this->getStore();        
        $config = $this->getProtectedPropertyFromObject('_config',$store);        
        $config->setValue($config_path,$value,'store', $this->getStore()->getCode());  
        return $this;
    }
    
    public function isAdmin()
    {
        if(!$this->isMage2())
        {
            return $this->getStore()->isAdmin();
        }

        $dir = $this->_getDirForNamepsaceProtection();        
        return include $dir . '/isAdmin.php';
    }
    
    public function getAppState()
    {
        return $this->getObjectManager()->get('Magento\App\State');
    }    
    
    const MAGE1_TEXT_LOOKUP_INSTRUCTIONS = 'Enter an alias (<span class="classname">catalog/product</span>) or a class name (<span class="classname">Mage_Catalog_Model_Product</span>).</p>';
    const MAGE2_TEXT_LOOKUP_INSTRUCTIONS = 'Enter a Magento 2 alias (<span class="classname">such as Magento\\Catalog\\Model\\Product</span>)';
    public function getTextLookupInstructions()
    {
        if(!$this->_isMage2())
        {
            return self::MAGE1_TEXT_LOOKUP_INSTRUCTIONS;     
        }
        return self::MAGE2_TEXT_LOOKUP_INSTRUCTIONS;         
    }
}