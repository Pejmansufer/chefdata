<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
class Chefsdeal_Commercebug_Model_Observer_Dot
{		
    const RENDER_FULL_LABELS		= true;
    static protected $_stack   		= array();
    static protected $_elementStack = array();
    
    static protected $_graphs 		= array();
    static protected $_definitions	= array();
    
    public function before($observer)
    {
        $name = $observer->getBlock()->getNameInLayout();
        $name = $name ? $name : 'unknown';		
        self::$_stack[] = $name;
    }
    
    public function afterElement($observer)
    {
        $name = $observer->getElementName();
        self::$_elementStack[] = $name;
    }
    
    public function after($observer)
    {			
        $this->_watch($observer);
        array_pop(self::$_stack);
        self::renderGraph();
    }
    
    static public function shouldRenderFullLabels()
    {
        return self::RENDER_FULL_LABELS;
    }
    
    static public function getShim()
    {
        $shim = Chefsdeal_Commercebug_Model_Shim::getInstance();
        return $shim;	       
    }
    static protected function getDotStart()
    {
        $graph = 'digraph g {

ranksep=6
node [
    fontsize = "16"
    shape = "rectangle"
    width =3
    height =.5
];
edge [
];         ';
        return $graph;
    }
    
    static public function renderGraphMage2()
    {
        $shim = self::getShim();
        $layout = $shim->getSingleton('core/layout');
        $graph = self::getDotStart();
        
        foreach(self::$_elementStack as $block_name)
        {
            $parent_name  = $layout->getParentName($block_name);      
            if(!$parent_name)
            {
                Chefsdeal_Commercebug_Model_Shim::Log('Skipping ' . $block_name . ', no parent');
                continue;
            }

            $line = '"' . $parent_name . '"' . '->' . 
                    '"' . $block_name . '"' . 
                    ' [style=solid]';
            self::$_graphs[] = $line;
            
            if($layout->isBlock($block_name) && $block = $layout->getBlock($block_name))
            {
                $template = $block->getTemplate() ? $block->getTemplate() : 'NO TEMPLATE';
                $definition = '"' . $block_name . '"' . '[label="' . 
                $block_name .  '\\\\n' . 
                str_replace('\\','\\\\',get_class($block)) . '\\\\n' . 
                $template . 
                '"]';            
            }
        
            if($layout->isContainer($block_name))
            {
                $definition = '"' . $block_name . '"' . '[label="' . 
                $block_name .  '\\\\n' . 
                'CONTAINER\n\n"]';
            }
            self::$_definitions[$block_name] = $definition;
        }        
      
        $graph .= implode(";\n",self::$_graphs) . ';';
        
        if(self::shouldRenderFullLabels())
        {
            $graph .= implode(";\n",self::$_definitions) . ';';
        }
        $graph .= '}';
        return $graph;    
    }
    
    static public function renderGraphMage1()
    {
        $graph = self::getDotStart();
        $graph .= implode(";\n",self::$_graphs) . ';';
        
        if(self::shouldRenderFullLabels())
        {
            $graph .= implode(";\n",self::$_definitions) . ';';
        }
        $graph .= '}';
        return $graph;
    }
    
    static public function renderGraph()
    {
        $shim = self::getShim();
        if($shim->isMage2())
        {
            return self::renderGraphMage2();
        }
        return self::renderGraphMage1();
    }
    
    protected function _getAssumedParentNameFromStack()
    {
        $index = count(self::$_stack)-2;
        return array_key_exists($index, self::$_stack) ? self::$_stack[$index] : 'Unknown Parent/Direct Instantiation';
    }
    
    /**
    * Happens with getChildChild html ... I think
    */		
    protected function _isAssumeParentAndRealParentMismatch($parent)
    {
        return (is_object($parent) && $parent->getNameInLayout() != $this->_getAssumedParentNameFromStack());
    }
    
    protected function _checkAssumptions($parent, $block)
    {
        if(is_object($parent) && $this->_isAssumeParentAndRealParentMismatch($parent))
        {
            // Mage::Log('Assumed Parent Mismatch');
            // Mage::Log('Real Parent: ' . $parent->getNameInLayout());
            // Mage::Log('Assumed Parent:' . $this->_getAssumedParentNameFromStack());
            // Mage::Log('Block: ' . $block->getNameInLayout());
            // Mage::Log(self::$_stack);
            // Mage::Log('END: Assumed Parent Mismatch');
        }
    }
    
    protected function _isUnwantedBlock($block)
    {
        //skip Commercebug Blocks
        return strpos(get_class($block), 'Commercebug') !== false;
    }
    
    protected function _isParentlessAnonymous($parent, $block)
    {
        return !is_object($parent) && strpos($block->getNameInLayout(),'ANONYMOUS_') === 0;
    }
    
    protected function _isGoofyFacade($parent, $block)
    {
        return is_object($parent) && ($parent instanceof Mage_Core_Block_Template_Facade);
    }
    
    protected function _watch($observer)
    {			
        $block 	= $observer->getBlock();

        if($this->_isUnwantedBlock($block)) 
        { 
            return; 
        } 
        
        $style = ' [style=solid]';			
        $parent 		= $block->getParentBlock();			
        $name_block  	= $block->getNameInLayout();
        $name_parent  	= 'unknown';
        
        $this->_checkAssumptions($parent, $block);
        
        if($this->_isParentlessAnonymous($parent, $block) )
        {						
            $style = ' [style=dashed]';
            $parent = new Varien_Object();
            $parent->setNameInLayout($this->_getAssumedParentNameFromStack() );
            $name_parent = $parent->getNameInLayout();
        }
        else if ($this->_isAssumeParentAndRealParentMismatch($parent, $block))
        {
            $style = ' [style=dotted]';
            $parent = new Varien_Object();
            $parent->setNameInLayout($this->_getAssumedParentNameFromStack());
            $name_parent = $parent->getNameInLayout();							
        }
        else if ($this->_isGoofyFacade($parent, $block))
        {
            $style = ' [style=dotted]';
            $parent = new Varien_Object();
            $parent->setNameInLayout($this->_getAssumedParentNameFromStack());
            $name_parent = $parent->getNameInLayout();				
        }
        else if (is_object($parent))
        {
            $name_parent = $parent->getNameInLayout();
        }
        
        if(!$parent)
        {
            self::$_graphs[] = '"' . $block->getNameInLayout() . '"' . $style;					
        }
        else
        {			
            self::$_graphs[] = '"' . $parent->getNameInLayout() . '"' . '->' . '"' . $block->getNameInLayout() . '"' . $style;					
        }	
        
        $template = $block->getTemplate() ? $block->getTemplate() : 'NO TEMPLATE';
        $definition = '"' . $name_block . '"' . '[label="' . 
        $name_block .  '\\\\n' . 
        get_class($block) . '\\\\n' . 
        $template . 
        '"]';
        
        self::$_definitions[$name_block] = $definition;
    }
    
    protected function _checkInside()
    {
    }
}