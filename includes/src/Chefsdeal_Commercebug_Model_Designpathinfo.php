<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
class Chefsdeal_Commercebug_Model_Designpathinfo extends Varien_Object
{
    public function __construct(array $data = array())	
    {
        parent::__construct();
        $this->_construct();
    }
    
    public function getDesignInformationMage2()
    {
        
        $shim = $this->getShim();
        $params = $shim->getObjectManager()->get('Magento\Core\Model\View\Design')->getDesignParams();
        $theme = $params['themeModel'];
        $custom = $this->_getCustomData();
        
		$data = (array(
		'package'					=> 'A',
		'translations'				=> 'B',
		'templates'					=> 'C',
		'skin'						=> 'D',
		'layout'					=> 'E',
		'default_theme'				=> 'F',
		'custom_theme' 				=> $custom['theme'],
		'custom_package' 			=> $custom['package'],		
		));
		
        return $data;
    }
    
    public function getDesignInformationMage1()
    {
        $design = Mage::getDesign();
        $custom = $this->_getCustomData();
		$data = (array(
		'package'					=> $design->getPackageName(),
		'translations'				=> $design->getLocaleBaseDir(array()),
		'templates'					=> $design->getTemplateFilename(''),
		'skin'						=> $design->getSkinBaseDir(array()),
		'layout'					=> $design->getLayoutFilename(''),
		'default_theme'				=> $design->getTheme(''),
		'custom_theme' 				=> $custom['theme'],
		'custom_package' 			=> $custom['package'],			
		));
		

		//yay global singletons
		if(class_exists('Mage_Core_Model_Design_Config', false) && $config = Mage::getSingleton('core/design_config'))
		{		 
		    $data['parent_theme'] = 'none';
		    $area       = $design->getArea();
		    $package    = $design->getPackageName();
		    $theme      = $design->getTheme('');		    
            $parent     = (string)$config->getNode($area . '/' . $package . '/' . $theme . '/parent');
            if($parent)
            {
                $data['parent_theme'] = $parent;
            }
		}
		
		foreach($data as $key=>$value)
		{
			if(strpos($value,Mage::getBaseDir()) == 0) //first position, not false
			{
				$data[$key] = trim(
				str_replace(Mage::getBaseDir(),'',$value)
				,'/');
			}
		}
		return $data;
    }
    
	public function _construct()
	{		
	    if($this->getShim()->isMage2())
	    {
    	    $data = $this->getDesignInformationMage2();
	    }
	    else
	    {
		    $data = $this->getDesignInformationMage1();
		}
		$this->setData($data);
	}
	
	protected function _getCustomData()
	{
	    $shim = $this->getShim();
		$designChange = $this->getShim()->getSingleton('core/design')
		->loadChange($shim->getStore()->getStoreId());	
		
		$data = array(
		'theme'		=>'N/A',
		'package'	=>'N/A'
		);
		$change_data = $designChange->getData();

		if($change_data && array_key_exists('theme',$change_data))
		{
			$data['theme']   = $change_data['theme'];
			$data['package'] = $change_data['package'];
		}
		return $data;
	}

	public function research()
	{
			var_dump('##Mage::getDesign()->getTheme(\'\')');
			var_dump(Mage::getDesign()->getTheme(''));

			var_dump('##Default Theme');
			var_dump(Mage::getDesign()->getDefaultTheme());
			
			var_dump('##Templates');
			var_dump(Mage::getDesign()->getTemplateFilename(''));

			var_dump('##Layout');
			var_dump(Mage::getDesign()->getLayoutFilename(''));
			
			var_dump('###Package');
			var_dump(Mage::getDesign()->getPackageName());
			
			
			var_dump('##Skin');			
			var_dump(
			Mage::getDesign()->getSkinBaseDir(array())
			);						
			
			var_dump('##base skin');
			var_dump(Mage::getBaseDir('skin'));
			
			
			var_dump(
			Mage::getDesign()->getBaseDir(array())
			);		

			var_dump('Nothing for design? Probably dno\'t need');
			var_dump(Mage::getBaseDir('design'));
			
			
			
			var_dump('##Translations');
			var_dump(
			Mage::getDesign()->getLocaleBaseDir(array())
			);		
	
	}
	
    public function getShim()
    {
        $shim = Chefsdeal_Commercebug_Model_Shim::getInstance();
        return $shim;	    
    }	
}
