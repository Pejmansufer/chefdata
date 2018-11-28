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
 
class Magmodules_Reviewemail_Block_Adminhtml_Coupons_Grid extends Mage_Adminhtml_Block_Widget_Grid {

	public function __construct() {
		parent::__construct();
		$this->setId('couponsGrid');
		$this->setDefaultSort('expiration_date');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection() {
		$coupon_table = Mage::getSingleton('core/resource')->getTableName('salesrule/coupon');
		$collection = Mage::getModel('reviewemail/coupons')->getCollection();
		$collection->getSelect()->join(array('coupontable' => $coupon_table), 'main_table.coupon_id = coupontable.coupon_id', array('coupontable.*'));
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns() {
    
		$this->addColumn('coupon_id', array(
			'header'    => Mage::helper('reviewemail')->__('Coupon ID'),
			'width'     => '50px',
			'index'     => 'coupon_id',
		));

		$this->addColumn('reviewemail_id', array(
			'header'    => Mage::helper('reviewemail')->__('Reviewemail ID'),
			'width'     => '50px',
			'index'     => 'reviewemail_id',
		));

		$this->addColumn('increment_id', array(
			'header'    => Mage::helper('reviewemail')->__('Order id'),
			'align'     =>'left',
			'width'     => '130px', 
			'index'     => 'increment_id',
		));
		
		$this->addColumn('customer_name', array(
			'header'    => Mage::helper('reviewemail')->__('Customer Name'),
			'align'     =>'left',
			'index'     => 'customer_name',
		));

		$this->addColumn('code', array(
			'header'    => Mage::helper('reviewemail')->__('Code'),
			'index'     => 'code',
		));     

		$this->addColumn('times_used', array(
			'header'    => Mage::helper('reviewemail')->__('Times Used'),
			'align'     =>'left',
			'index'     => 'times_used',
			'width'     => '50px',          
		));

		$this->addColumn('expiration_date', array(
			'header'    => Mage::helper('reviewemail')->__('Expiration Date'),
			'align'     =>'left',
			'index'     => 'expiration_date',
			'type'      => 'datetime', 
			'width'     => '180px',
			'gmtoffset' => true,
			'default'	=> ' ---- ',
		));              
 
		return parent::_prepareColumns();
	}

  	protected function _prepareMassaction() {
        $this->setMassactionIdField('coupon_id');
        $this->getMassactionBlock()->setFormFieldName('coupons');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('reviewemail')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('reviewemail')->__('Are you sure?')
        ));

		$this->getMassactionBlock()->addItem('activate', array(
			 'label'    => Mage::helper('reviewemail')->__('Reactivate'),
			 'url'      => $this->getUrl('*/*/massActivate'),
		));
		
		$this->getMassactionBlock()->addItem('extend', array(
			 'label'    => Mage::helper('reviewemail')->__('Extend by 14 days'),
			 'url'      => $this->getUrl('*/*/massExtend'),
		));     
		       
        return $this;
    }

	public function getRowUrl($row) {
		return '';
	}

}