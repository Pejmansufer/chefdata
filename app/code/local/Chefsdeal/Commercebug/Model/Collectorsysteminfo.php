<?php 
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
class Chefsdeal_Commercebug_Model_Collectorsysteminfo extends Chefsdeal_Commercebug_Model_Observingcollector
{
    protected $_items;
    public function collectInformation($observer)
    {
        if(!$this->_isOn($observer))
        {
            return;
        }
        $collection = $this->getCollector();
        $system_info = new stdClass();			
        $system_info->ajax_path = $this->getUrl();

        if(!$this->getShim()->isMage2() && Mage::getIsDeveloperMode())
        {
            Chefsdeal_Commercebug_Model_Crossareaajax::validateControllers();
        }
        
        $system_info->ajax_paths = new stdClass;
        $system_info->form_key = $this->getShim()->getSingleton('core/session')->getFormKey();
        foreach(Chefsdeal_Commercebug_Model_Crossareaajax::getAjaxActionNames() as $action)
        {
            $system_info->ajax_paths->{$action} = $this->createAjaxUrl($action);
        }
        $this->_items['system_info'] = $system_info;
    }

    public function createAjaxUrl($action)
    {
        $shim = $this->getShim();
        $alias              = 'core/url';    
        $module_controller  = 'commercebug/ajax';
                
        if($shim->isAdmin())
        {     
            $alias  = 'adminhtml/url';
            $module_controller= 'commercebugadmin/adminajax';
        }
        
        #Chefsdeal_Commercebug_Model_Shim::Log('Mage2 Cleanup in ' . __CLASS__ . ' ' . __LINE__);
        if($alias=='adminhtml/url' && $shim->isMage2())
        {
            $alias = 'backend/url';
        }
        
        $url_model  = $shim->getModel($alias);
        $url = $url_model->getUrl($module_controller.'/'.$action,
                array('_secure'=>$shim->getModel('core/store')->isCurrentlySecure()));        
                
        return $url;
    }
    
    /**
    * Returns a base URL for AJAX Requests
    */		
    public function getUrl()
    {
        $shim = $this->getShim();
        $fake = 'fakeactiontotrimoff/'; //not clear if 'commercebug/ajax/' route will return "index" in the URL or not
        
        $alias      = 'core/url';
        $str_url    = 'commercebug/ajax';
        
        if($shim->isAdmin())
        {     
            $alias  = 'adminhtml/url';
            $str_url= 'commercebugadmin/adminajax';
            if($shim->isMage2())
            {
                $str_url .= '/adminajax';                
            }
        }
        
        #Chefsdeal_Commercebug_Model_Shim::Log('Cleanup in ' . __CLASS__ . ' ' . __LINE__);
        if($alias=='adminhtml/url' && $shim->isMage2())
        {
            $alias = 'backend/url';
        }
        
        $url_model  = $shim->getModel($alias);
        
        $url = str_replace($fake,'',
            $url_model->getUrl($str_url.'/'.$fake,
                array('_secure'=>$shim->getModel('core/store')->isCurrentlySecure())));
        if($url[strlen($url)-1] == '/')
        {
            $url = substr($url, 0, strlen($url)-1);
        }
        
        return $url;
    }
    
    public function addToObjectForJsonRender($json)
    {
        $json->system_info = new stdClass();
        if(is_object($this->_items['system_info']))
        {
            $json->system_info = $this->_items['system_info'];
        }
        return $json;
    }
    
    public function createKeyName()
    {
        return 'systeminfo';
    }
}