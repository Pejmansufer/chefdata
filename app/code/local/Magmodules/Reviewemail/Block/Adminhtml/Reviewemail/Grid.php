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
 
class Magmodules_Reviewemail_Block_Adminhtml_Reviewemail_Grid extends Mage_Adminhtml_Block_Widget_Grid {

	public function __construct() {
		parent::__construct();
		$this->setId('reviewemailGrid');
		$this->setDefaultSort('sheduled_at');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection() {
		$collection = Mage::getModel('reviewemail/reviewemail')->getCollection();
		$collection->addFieldToFilter('status',array('eq'=>'scheduled'));
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns() {

		$this->addColumn('reviewemail_id', array(
			'header'    => Mage::helper('reviewemail')->__('Reminder Id'),
			'align'     =>'right',
			'width'     => '50px',
			'index'     => 'reviewemail_id',
		));

		if (!Mage::app()->isSingleStoreMode()) {
			$this->addColumn('store_id', array(
				'header'    => Mage::helper('reviewemail')->__('Store'),
				'index'     => 'store_id',
				'type'      => 'store',
				'width'     => '180px',            
				'store_view'=> true,
			));
		}

		$this->addColumn('increment', array(
			'header'    => Mage::helper('reviewemail')->__('Order id'),
			'align'     =>'left',
			'width'     => '130px', 
			'index'     => 'increment',
		));

		$this->addColumn('action', array(
			'header'    => Mage::helper('reviewemail')->__('View order'),
			'width'     => '80px',
			'align'     => 'center',
			'type'      => 'action',
			'getter'     => 'getOrderId',
			'filter'    => false,
			'sortable'  => false,
			'index'     => 'order_id',		
			'actions'   => array(
				array(
					'caption' => Mage::helper('reviewemail')->__('View Order'),
					'url'     => array('base'=>'adminhtml/sales_order/view'),
					'field'   => 'order_id'
				)
			),
		)); 

		$this->addColumn('comments', array(
			'header'    => Mage::helper('reviewemail')->__('Order comments'),
			'align'     => 'center',
			'width'     => '10px',           
			'index'     => 'reviewemail_id',
			'renderer'  => 'reviewemail/adminhtml_comments_renderer_comments',
			'filter'    => false,
			'sortable'  => false
		));

		$this->addColumn('customer_name', array(
			'header'    => Mage::helper('reviewemail')->__('Customer Name'),
			'align'     =>'left',
			'index'     => 'customer_name',
		));      

		$this->addColumn('customer_email', array(
			'header'    => Mage::helper('reviewemail')->__('Customer E-Mail'),
			'index'     => 'customer_email',
		));      

		$this->addColumn('created_at', array(
			'header'    => Mage::helper('reviewemail')->__('Created At'),
			'align'     =>'left',
			'index'     => 'created_at',
			'type'      => 'datetime', 
			'width'     => '180px',
			'gmtoffset' => true,
			'default'	=> ' ---- ',
		));

		$this->addColumn('sheduled_at', array(
			'header'    => Mage::helper('reviewemail')->__('Scheduled At'),
			'align'     =>'left',
			'index'     => 'sheduled_at',
			'type'      => 'datetime', 
			'width'     => '180px',
			'gmtoffset' => true,
			'default'	=> ' ---- ',
		));
		
		$this->addColumn('email_id', array(
			'header'    => Mage::helper('reviewemail')->__('Email'),
			'index'     => 'email_id',
			'width'     => '180px',
			'type'      => 'options',
			'options'   => array(
				'1' 	=> Mage::helper('reviewemail')->__('First Email'),
				'2' 	=> Mage::helper('reviewemail')->__('Second Email'),
			),
		));

		$this->addColumn('status', array(
			'header'    => Mage::helper('reviewemail')->__('Status'),
			'index'     => 'status',
			'type'      => 'options',
			'width'     => '100px',
			'options'   => array(
				'scheduled' => Mage::helper('reviewemail')->__('Scheduled'),
			),
		));

		return parent::_prepareColumns();
	}

	protected function _prepareMassaction() {

		$this->setMassactionIdField('reviewemail_id');
		$this->getMassactionBlock()->setFormFieldName('reviewemail');

		$this->getMassactionBlock()->addItem('delete', array(
			'label'    => Mage::helper('reviewemail')->__('Delete'),
			'url'      => $this->getUrl('*/*/massDelete'),
			'confirm'  => Mage::helper('reviewemail')->__('Are you sure?')
		));

		$this->getMassactionBlock()->addItem('sendreviewemail', array(
			'label'    => Mage::helper('reviewemail')->__('Send review email'),
			'url'      => $this->getUrl('*/*/sendReviewemail'),
			'confirm'  => Mage::helper('reviewemail')->__('Are you sure?')
		));

		return $this;
	}

	public function getRowUrl($row) {
		return;
	}

}