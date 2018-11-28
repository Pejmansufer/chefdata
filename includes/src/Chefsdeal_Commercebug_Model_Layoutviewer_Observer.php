<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
class Chefsdeal_Commercebug_Model_Layoutviewer_Observer extends Varien_Object{
    const FLAG_SHOW_LAYOUT 			= 'showLayout';
    const FLAG_SHOW_LAYOUT_FORMAT 	= 'showLayoutFormat';		
    const FLAG_SHOW_LAYOUT_FILES 	= 'showLayoutFiles';		
    const HTTP_HEADER_TEXT			= 'Content-Type: text/plain';
    const HTTP_HEADER_HTML			= 'Content-Type: text/html';
    const HTTP_HEADER_XML			= 'Content-Type: application/xml';
    
    private $request;
    
    private function init() {    
        $shim = $this->getShim();
        $app = $shim->getApp();        
        $layout = $shim->getLayout();
        //$this->setLayout($app->getFrontController()->getAction()->getLayout());
        $this->setLayout($layout);
        $this->setUpdate($this->getLayout()->getUpdate());
    }
    
    //entry point
    public function checkForLayoutDisplayRequest($observer) {	
        $shim= $this->getShim();
        if(!$shim->helper('commercebug')->isModuleOutputEnabled())
        {	
            return;
        }
        $this->init();
        $is_set = array_key_exists(self::FLAG_SHOW_LAYOUT, $_GET);
        if(		$is_set && 'package' == $_GET[self::FLAG_SHOW_LAYOUT]) {
            $this->outputPackageLayout();
        }
        else if($is_set && 'page'    == $_GET[self::FLAG_SHOW_LAYOUT]) {
            $this->outputPageLayout();			
        }
        else if($is_set && 'handles' == $_GET[self::FLAG_SHOW_LAYOUT]) {
            $this->outputHandles();
        }
        
        if(array_key_exists(self::FLAG_SHOW_LAYOUT_FILES, $_GET))
        {
            header('Content-Type: text/plain');
            foreach($this->getXmlFiles() as $file)
            {
                echo $file, "\n";
            }
            exit;
        }
    }

    private function outputHandles() {
        $update = $this->getUpdate();
        $handles = $update->getHandles();
        echo '<h1>','Handles For This Request','</h1>'."\n";
        echo '<ol>' . "\n";
        foreach($handles as $handle) {
            echo '<li>',$handle,'</li>';
        }
        echo '</ol>' . "\n";			
        die();
    }
    
    private function outputHeaders() {
        $is_set = array_key_exists(self::FLAG_SHOW_LAYOUT_FORMAT,$_GET);			
        $header		= self::HTTP_HEADER_XML;
        if($is_set && 'text' == $_GET[self::FLAG_SHOW_LAYOUT_FORMAT]) {
            $header = self::HTTP_HEADER_TEXT;
        }
        header($header);
    }
    
    private function outputPageLayout() {
        $layout = $this->getLayout();
        $this->outputHeaders();		
        die($layout->getNode()->asXML());		
    }
    
    private function outputPackageLayout() {
        $update = $this->getUpdate();
        $this->outputHeaders();
        
        if($this->getShim()->isMage2())
        {
            exit($update->getFileLayoutUpdatesXml()->asXml());
        }
        die($update->getPackageLayout()->asXML());
    }
    
    public function getShim()
    {
        $shim = Chefsdeal_Commercebug_Model_Shim::getInstance();
        return $shim;
    }	

    protected function _findFilePath($file,$base)
    {
        $file = Mage::getDesign()->getLayoutFilename($file);
        $file = trim(str_replace($base, '',$file),'/');	
        return $file;
    }
    
    public function getXmlFiles()
    {
        $files = array();
        
        //configured files
        $nodes = Mage::app()->getConfig()->getNode('frontend/layout/updates');
        $base = Mage::getBaseDir('design');
        foreach($nodes->children() as $node)
        {
            $files[] = $this->_findFilePath((string)$node->file,$base);
        }
        
        //now theme.xml files
        if(class_exists('Mage_Core_Model_Design_Config', false))
        {
            $design     = Mage::getDesign();
            $config     = Mage::getSingleton('core/design_config');
            $area       = $design->getArea();
            $package    = $design->getPackageName();
            $theme      = $design->getTheme('');                   
            $themeUpdates = $config->getNode("$area/$package/$theme/layout/updates");
            if($themeUpdates)
            {
                foreach($themeUpdates->children() as $node)
                {
                    $files[] = $this->_findFilePath( (string)$node->file, $base );
                }
            }
        }
        $files[] = $this->_findFilePath('local.xml',$base);
        return $files;
    }		
}