<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */

class Trmmarketing_Promotionalpopup_Block_Adminhtml_Promotionalpopup_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('promotionalpopupGrid');
      $this->setDefaultSort('promotionalpopup_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('promotionalpopup/promotionalpopup')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('promotionalpopup_id', array(
          'header'    => Mage::helper('promotionalpopup')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'promotionalpopup_id',
      ));

      $this->addColumn('title', array(
          'header'    => Mage::helper('promotionalpopup')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));

	  /*
      $this->addColumn('content', array(
			'header'    => Mage::helper('promotionalpopup')->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
	  */
	  $this->addColumn('popupviews', array(
          'header'    => Mage::helper('promotionalpopup')->__('Views'),
          'align'     =>'left',
		  'width'     => '80px',
          'index'     => 'popupviews',
      ));
	  
	  $this->addColumn('popupconversions', array(
          'header'    => Mage::helper('promotionalpopup')->__('Conversions'),
          'align'     =>'left',
		  'width'     => '80px',
          'index'     => 'popupconversions',
      ));
	  
	  $this->addColumn('sort_order', array(
          'header'    => Mage::helper('promotionalpopup')->__('Sort Order'),
          'align'     =>'left',
		  'width'     => '80px',
          'index'     => 'sort_order',
      ));

      $this->addColumn('status', array(
          'header'    => Mage::helper('promotionalpopup')->__('Open Automatically'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('promotionalpopup')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('promotionalpopup')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addColumn('action',
            array(
                'header'    =>  Mage::helper('promotionalpopup')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('promotionalpopup')->__('Preview'),
                        'url'       => array('base'=> '../index.php/promotionalpopup/index/index'),
						'target'    => '_blank',
                        'field'     => 'promotionalpopup_id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('promotionalpopup')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('promotionalpopup')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('promotionalpopup_id');
        $this->getMassactionBlock()->setFormFieldName('promotionalpopup');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('promotionalpopup')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('promotionalpopup')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('promotionalpopup/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('promotionalpopup')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('promotionalpopup')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}