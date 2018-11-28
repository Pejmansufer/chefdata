<?php
/**
 * Magmodules.eu - http://www.magmodules.eu - info@magmodules.eu
 * =============================================================
 * NOTICE OF LICENSE [Single domain license]
 * This source file is subject to the EULA that is
 * available through the world-wide-web at:
 * http://www.magmodules.eu/license-agreement/
 * =============================================================
 * @category    Magmodules
 * @package     Magmodules_Reviewemail
 * @author      Magmodules <info@magmodules.eu>
 * @copyright   Copyright (c) 2015 (http://www.magmodules.eu)
 * @license     http://www.magmodules.eu/license-agreement/  
 * =============================================================
 */
 
class Magmodules_Reviewemail_Block_Adminhtml_Exclude_Grid extends Mage_Adminhtml_Block_Widget_Grid {

	public function __construct() {
		parent::__construct();
		$this->setId('excludeGrid');
		$this->setDefaultSort('exclude_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection() {
		$collection = Mage::getModel('reviewemail/exclude')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns() {

		$this->addColumn('exclude_id', array(
			'header'    => Mage::helper('reviewemail')->__('Id'),
			'align'     =>'right',
			'width'     => '50px',
			'index'     => 'exclude_id',
		));

		if(!Mage::app()->isSingleStoreMode()) {
			$this->addColumn('store_id', array(
				'header'    => Mage::helper('reviewemail')->__('Store'),
				'index'     => 'store_id',
				'type'      => 'store',
				'width'     => '180px',            
				'store_view'=> true,
			));
		}

		$this->addColumn('email', array(
			'header'    => Mage::helper('reviewemail')->__('Email'),
			'align'     =>'left',
			'width'     => '130px', 
			'index'     => 'email',
		));

		$this->addColumn('date', array(
			'header'    => Mage::helper('reviewemail')->__('Date'),
			'align'     =>'left',
			'index'     => 'date',
			'type'      => 'datetime', 
			'width'     => '180px',
			'gmtoffset' => true,
			'default'   => ' ---- ',
		));

		$this->addColumn('status', array(
			'header'    => Mage::helper('reviewemail')->__('Status'),
			'index'     => 'status',
			'type'      => 'options',
			'width'     => '100px',
			'options'   => array(
				'1' 	=> Mage::helper('reviewemail')->__('Added through admin'),
				'2' 	=> Mage::helper('reviewemail')->__('Opt-out through email'),    		
			),
		));

		return parent::_prepareColumns();
	}

	protected function _prepareMassaction() {
		return $this;
	}

	public function getRowUrl($row) {
		return;
	}

}