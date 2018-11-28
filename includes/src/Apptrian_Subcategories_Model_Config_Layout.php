<?php
/**
 * @category   Apptrian
 * @package    Apptrian_Subcategories
 * @author     Apptrian
 * @copyright  Copyright (c) 2014 Apptrian (http://www.apptrian.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptrian_Subcategories_Model_Config_Layout
{
    
    protected $_options;
	const LAYOUT_GRID = 'subcategories-layout-grid';
    const LAYOUT_LIST = 'subcategories-layout-list';
    
    public function toOptionArray()
    {
        if (!$this->_options) {
			$this->_options[] = array(
			   'value'=>self::LAYOUT_GRID,
			   'label'=>Mage::helper('apptrian_subcategories')->__('Grid')
			);
			$this->_options[] = array(
			   'value'=>self::LAYOUT_LIST,
			   'label'=>Mage::helper('apptrian_subcategories')->__('List')
			);		
		}
		return $this->_options;
	}
    
}