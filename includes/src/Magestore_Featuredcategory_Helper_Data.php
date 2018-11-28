<?php

class Magestore_Featuredcategory_Helper_Data extends Mage_Core_Helper_Abstract
{
	protected $_cat;
	
	public function init($cat)
	{
		$this->_cat = $cat;
		return $this;	
	}
	
	public function resize($width=100,$height=null)
	{
		$height = $height ? $height : $width;
		$this->createCacheFolder($width,$height);
		$orgFolder = $this->getOrgFolder();
		$cacheSizeFolder = $this->getCacheSizeFolder($width,$height);	
		
		$imageFile = $this->_cat->getImage();
		if(!file_exists($cacheSizeFolder.DS.$imageFile))
		{
			if(file_exists($orgFolder.DS.$imageFile))
			{
				$fileImg = new Varien_Image($orgFolder.DS.$imageFile);
				$fileImg->keepAspectRatio(true);
				$fileImg->keepFrame(true);
				$fileImg->keepTransparency(true);
				$fileImg->constrainOnly(false);
				$fileImg->backgroundColor(array(255,255,255));
				$fileImg->resize($width,$height);
				$fileImg->save($cacheSizeFolder.DS.$imageFile,null);
			}
		}
		return Mage::getBaseUrl('media').'catalog/category/cache/'.$this->_cat->getId().'/'.$width.'x'.$height.'/'.$imageFile;
	}
	
	public function getOrgFolder()
	{
		return Mage::getBaseDir('media').DS.'catalog/category';
	}
	
	public function getCacheFolder()
	{
		return Mage::getBaseDir('media').DS.'catalog/category'.DS.'cache'.DS.$this->_cat->getId();
	}
	
	public function getCacheSizeFolder($width,$height)
	{
		return $this->getCacheFolder().DS.$width.'x'.$height;
	}
	
	public function createCacheFolder($width,$height)
	{
		$orgFolder = $this->getOrgFolder();
		$cacheFolder = $this->getCacheFolder();
		$cacheSizeFolder = $this->getCacheSizeFolder($width,$height);
		
		if(!is_dir($orgFolder))
		{
			try{
			
				mkdir($orgFolder);
				
				chmod($orgFolder,0777);
				
			} catch(Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}			
		
		if(!is_dir($cacheFolder))
		{
			try{
			
				mkdir($cacheFolder);
				
				chmod($cacheFolder,0777);
				
			} catch(Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}	
		if(!is_dir($cacheSizeFolder))
		{
			try{
			
				mkdir($cacheSizeFolder);
				
				chmod($cacheSizeFolder,0777);
				
			} catch(Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}			
	}
}