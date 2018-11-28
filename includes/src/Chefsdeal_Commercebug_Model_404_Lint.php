<?php
class Chefsdeal_Commercebug_Model_404_Lint extends Varien_Object
{
    const STATE_NEUTRAL   = 0;
    const STATE_CLASS_DEF = 1;
    
    protected $_originalPath = false;
    protected $_inferedModule;
    protected $_inferedController;    
    protected $_inferedAction;    
    
    protected $_routers=array(); 
    protected $_claimed=array();
    protected $_controllerInformation=array();
    public function _construct()
    {

    }    

    /**
    * Moved from constructor so we only config scan if its needed
    */
    protected function _init()
    {
        $this->_initRouters();
        $this->_initClaimed();    
    }
    
    protected function _getConfigNodesWithRouters()
    {
        return array('frontend', 'admin');
    }
    
    protected function _initClaimed()
    {
        $module = $this->getUrlModuleName();
        $this->_claimed = array();
                
        $nodes  = $this->_getConfigNodesWithRouters();
        foreach($nodes as $node)
        {
            //front name
            foreach($this->_routers[$node] as $router)
            {
                $args = $router->args;
                if(!$args)
                {
                    continue;
                }
                if((string)$args->frontName == $module)
                {
                    $this->_claimed[] = $router;
                }
            }
        }
        return $this->_claimed;
    }
    
    public function getClaimed()
    {
        return $this->_claimed;
    }
    
    protected function _initRouters()
    {
        $config     = Mage::getConfig();
        $nodes  = $this->_getConfigNodesWithRouters();
        foreach($nodes as $node)
        {
            $frontend   = $config->getNode($node);
            foreach($frontend->routers->children() as $router)
            {
                $this->_routers[$node][$router->getName()] = $router;
            }
        }  
    }

    public function getUrlOriginalPath()
    {
        if(!$this->_originalPath)
        {
            $this->_originalPath = Mage::app()->getRequest()->getOriginalPathInfo();
        }
        return $this->_originalPath;
    }  
    
    public function getUrlModuleName()
    {
        if(!$this->_inferedModule)
        {            
            $path = $this->getUrlOriginalPath();
            $path = trim($path, '/');
            $path = explode('/', $path);
            $this->_inferedModule = array_shift($path);
        }
        return $this->_inferedModule;        
    }
    
    public function getUrlControllerName()
    {
        if(!$this->_inferedController)
        {
            $path = $this->getUrlOriginalPath();
            $path = trim($path, '/');
            $path = explode('/', $path);
            $this->_inferedController = array_key_exists(1,$path) ? $path[1] : 'index';
        }
        return $this->_inferedController;         
    }

    public function getUrlActionName()
    {
        if(!$this->_inferedAction)
        {
            $path = $this->getUrlOriginalPath();
            $path = trim($path, '/');
            $path = explode('/', $path);
            $this->_inferedAction = array_key_exists(2,$path) ? $path[2] : 'index';
        }
        return $this->_inferedAction;         
    }
    
    public function getControllerInformation($module_name)
    {
        if(!$this->_controllerInformation)
        {
            $router_object = new Mage_Core_Controller_Varien_Router_Standard;        
            $this->_controllerInformation = array();                    
            $this->_controllerInformation['class_file'] = $router_object->getControllerFileName($module_name, $this->getUrlControllerName());
            $this->_controllerInformation['class_name'] = $router_object->getControllerClassName($module_name, $this->getUrlControllerName());    
        }
        return $this->_controllerInformation;
    }
    
    public function getActionMethodExists($controller_name,$action)
    {    
        $info = $this->getControllerInformation($this->getUrlModuleName());
        require_once $info['class_file'];
        $r = new ReflectionClass($controller_name);
        return $r->hasMethod($action . 'Action');
    }
    
    public function getClaimedByName()
    {
        $claimed = $this->getClaimed();
        return (string) $claimed[0]->args->module;
    }
    
    public function getControllerClassExists($module_name)
    {
        $info = $this->getControllerInformation($module_name);
        $tokens = token_get_all(file_get_contents($info['class_file']));    
        
        $state = self::STATE_NEUTRAL;
        foreach($tokens as $token)
        {
            if(!is_array($token))   //skip single character tokens
            {
                continue;
            }
            $constant_value = $token[0];
            $real_value     = $token[1];
            $token_name     = token_name($constant_value);
            if($token_name == 'T_CLASS')
            {
                $state = self::STATE_CLASS_DEF;
            }            
            if($state != self::STATE_CLASS_DEF)
            {
                continue;
            }
            //first T_STRING after 
            if($token_name == 'T_STRING')
            {
                return $info['class_name'] == $real_value;
            }
        }
        return false;    
    }
    
    public function getExtraModules()
    {
        
        $claimed    = $this->getClaimed();
        $router     = array_shift($claimed);
        $args       = $router->args;
        if(!$args)
        {
            return array();
        }
        
        $modules    = $args->modules;
        if(!$modules)
        {
            return array();
        }
        
        $return = array();
        foreach($modules->children() as $module)
        {
            $return[] = (string) $module;
        }
        return $return;
    }
    
    /**
    * Heuristics to guess if this is a 404
    */        
    public function is404()
    {
        $response   = Mage::app()->getResponse();
        $headers    = $response->getHeaders();
        $status     = false;
        foreach($headers as $header)
        {
            if(array_key_exists('name', $header) && $header['name'] == 'Status')            
            {
                $status = $header['value'];
            }
        }

        $is404      = strpos($status, '404') !== false;
        if($is404)
        {
            return $is404;
        }

        //check the body content incase headers are incorrect
        $body       = $response->getBody();        
        $is404      = strpos($body, 'cms-no-route') !== false;        
        return $is404;    
    }

    public function getControllerFileExists($module_name)
    {
        $info = $this->getControllerInformation($module_name);
        return file_exists($info['class_file']);
    }
    
    public function getControllerClassName($module_name)
    {
        $info = $this->getControllerInformation($module_name);
        return $info['class_name'];
    }

    
    public function getControllerFilePath($module_name)
    {
        $info = $this->getControllerInformation($module_name);
        return $info['class_file'];
    }
    
//     public function __call($method, $args)
//     {
//         var_dump($method);
//         var_dump($args);
//         exit(__METHOD__);
//     }
    
    public function asStdClass()
    {
        if(!$this->is404())
        {
            return false;
        }
        
        $this->_init();
        $o = new stdClass;
        $o->originalUrlPath = $this->getUrlOriginalPath();        
        $o->urlModuleName = $this->getUrlModuleName();
        
        $o->claimed = $this->getClaimed();
        if(!$o->claimed)
        {
            return $o;
        }
        
        $o->claimedByName = $this->getClaimedByName();
        $o->extraModules = $this->getExtraModules();
        $o->extraModules = $this->getExtraModules();
        $o->urlControllerName = $this->getUrlControllerName();
        
        $claimed_by = $this->getClaimedByName();
        $info       = $this->getControllerInformation($claimed_by);
        
        $o->controllerFilePath = $this->getControllerFilePath($claimed_by);
        $o->urlControllerName = $this->getUrlControllerName();
        $o->controllerFileExists = $this->getControllerFileExists($claimed_by);
        
        if(!$o->controllerFileExists)
        {
            return $o;
        }
        
        $o->controllerClassName = $this->getControllerClassName($claimed_by);
        $o->controllerClassExists = $this->getControllerClassExists($claimed_by);
        if(!$o->controllerClassExists)
        {
            return $o;
        }
        
        
        $o->urlActionName = $this->getUrlActionName();
        
        $class_name = $info['class_name'];        
        $o->urlActionName = $this->getUrlActionName();
        $o->actionMethodExists = $this->getActionMethodExists($class_name, $this->getUrlActionName());
        
        return $o;
    }        
}