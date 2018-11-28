<?php
/**
 * WeaveApps_ResponsiveSlideshow Extension
 *
 * @category   WeaveApps
 * @package    WeaveApps_ResponsiveSlideshow
 * @copyright  Copyright (c) 2014 Weave Apps. (http://www.weaveapps.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *
 */
class WeaveApps_ResponsiveSlideshow_Block_Adminhtml_Responsiveslideshowgroup_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('responsiveslideshowgroupGrid');
        $this->setDefaultSort('group_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('responsiveslideshow/responsiveslideshowgroup')->getCollection();
         $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('group_id', array(
            'header' => Mage::helper('responsiveslideshow')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'group_id',
        ));

        $this->addColumn('group_name', array(
            'header' => Mage::helper('responsiveslideshow')->__('Group name'),
            'index' => 'group_name',
        ));
        
        $this->addColumn('transition_settings', array(
            'header' => Mage::helper('responsiveslideshow')->__('Transition'),
            'width' => '180px',
            'index' => 'transition_settings',
            'type' => 'options',
            'options' => array(
                'none' => 'none',
                'fade' => 'fade',
                'scrollLeft' => 'scrollLeft',
                'scrollRight' => 'scrollRight',
                'scrollDown' => 'scrollDown',
                'scrollUp' => 'scrollUp',
                'cover' => 'cover',
                'blindX' => 'blindX',
                'blindY' => 'blindY',
                'blindZ' => 'blindZ',
            ),
        ));
        
        $this->addColumn('easing_settings', array(
            'header' => Mage::helper('responsiveslideshow')->__('Easing effect'),
            'width' => '180px',
            'index' => 'easing_settings',
            'type' => 'options',
            'options' => array(
                'easeInOutSine' => 'easeInOutSine',
                'easeInBack' => 'easeInBack',
                'easeOutBack' => 'easeOutBack',
                'easeInOutQuint' => 'easeInOutQuint',
                'easeOutQuart' => 'easeOutQuart',
                'easeOutExpo' => 'easeOutExpo',
                'easeOutCirc' => 'easeOutCirc',
            ),
        ));

        $this->addColumn('banner_ids', array(
            'header' => Mage::helper('responsiveslideshow')->__('Banner Ids'),
            'index' => 'banner_ids',
        ));
         
        $this->addColumn('status', array(
            'header' => Mage::helper('responsiveslideshow')->__('Status'),
            'align' => 'left',
            'width' => '180px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => 'Enabled',
                2 => 'Disabled',
            ),
        ));

        $this->addColumn('action',
                array(
                    'header' => Mage::helper('responsiveslideshow')->__('Action'),
                    'width' => '50',
                    'type' => 'action',
                    'getter' => 'getId',
                    'actions' => array(
                        array(
                            'caption' => Mage::helper('responsiveslideshow')->__('Edit'),
                            'url' => array('base' => '*/*/edit'),
                            'field' => 'id'
                        )
                    ),
                    'filter' => false,
                    'sortable' => false,
                    'index' => 'stores',
                    'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('responsiveslideshow')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('responsiveslideshow')->__('XML'));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('group_id');
        $this->getMassactionBlock()->setFormFieldName('responsiveslideshow');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('responsiveslideshow')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('responsiveslideshow')->__('Are you sure?')
        ));
        $statuses = Mage::getSingleton('responsiveslideshow/status')->getOptionArray();
        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('responsiveslideshow')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('responsiveslideshow')->__('Status'),
                    'values' => $statuses
                )
            )
        ));
        return $this;
    }
    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}