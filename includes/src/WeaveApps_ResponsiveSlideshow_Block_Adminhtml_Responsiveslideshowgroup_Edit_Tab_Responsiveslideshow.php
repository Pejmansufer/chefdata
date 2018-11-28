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
class WeaveApps_ResponsiveSlideshow_Block_Adminhtml_Responsiveslideshowgroup_Edit_Tab_Responsiveslideshow extends WeaveApps_ResponsiveSlideshow_Block_Adminhtml_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('responsiveslideshowLeftGrid');
        $this->setDefaultSort('rs_id');
        $this->setUseAjax(true);
    }

    public function getresponsiveslideshowgroupData() {
        return Mage::registry('responsiveslideshowgroup_data');
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('responsiveslideshow/responsiveslideshow')->getCollection();
        $collection->getSelect()->order('rs_id');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _addColumnFilterToCollection($column) {
        if ($this->getCollection()) {
            if ($column->getId() == 'responsiveslideshow_triggers') {
                $responsiveslideshowIds = $this->_getSelectedresponsiveslideshows();
                if (empty($responsiveslideshowIds)) {
                    $responsiveslideshowIds = 0;
                }
                if ($column->getFilter()->getValue()) {
                    $this->getCollection()->addFieldToFilter('rs_id', array('in' => $responsiveslideshowIds));
                } else {
                    if ($responsiveslideshowIds) {
                        $this->getCollection()->addFieldToFilter('rs_id', array('nin' => $responsiveslideshowIds));
                    }
                }
            } else {
                parent::_addColumnFilterToCollection($column);
            }
        }
        return $this;;
    }

    protected function _prepareColumns() {
        $this->addColumn('responsiveslideshow_triggers', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'values' => $this->_getSelectedresponsiveslideshows(),
            'align' => 'center',
            'index' => 'rs_id'
        ));
        $this->addColumn('rs_id', array(
            'header' => Mage::helper('catalog')->__('ID'),
            'sortable' => true,
            'width' => '50',
            'align' => 'center',
            'index' => 'rs_id'
        ));


        $this->addColumn('title', array(
            'header' => Mage::helper('catalog')->__('Title'),
            'index' => 'title',
            'align' => 'left',
        ));

       $this->addColumn('banner_type', array(
          'header' => Mage::helper('responsiveslideshow')->__('Type'),
          'width' => '80px',
          'index' => 'banner_type',
          'type' => 'options',
          'options' => array(
              0 => 'Image',
              1 => 'Video',
              2 => 'Video',
          ),
      ));

        $this->addColumn('image', array(
          'header'    => Mage::helper('responsiveslideshow')->__('Image'),
          'align'     =>'left',
          'index'     => 'image',
      ));

        $this->addColumn('sort_order', array(
            'header' => Mage::helper('responsiveslideshow')->__('Sort Order'),
            'width' => '80px',
            'index' => 'sort_order',
            'align' => 'center',
        ));

        echo '<script type="text/javascript">$wa(document).ready(function(){ $wa("td.a-left").each(function(){var f1 = $wa(this);var t2=f1.html();t2=t2.replace(/&lt;img/g, "<img");t2=t2.replace(/&gt;/g, ">");t2 = t2.replace("yoururl/","'.Mage::getBaseUrl('media').'"); f1.html(t2);})});</script>';
        return parent::_prepareColumns();
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/responsiveslideshowgrid', array('_current' => true));
    }

    protected function _getSelectedresponsiveslideshows() {
        $responsiveslideshows = $this->getRequest()->getPost('selected_responsiveslideshows');
        if (is_null($responsiveslideshows)) {
            $responsiveslideshows = explode(',', $this->getresponsiveslideshowgroupData()->getBannerIds());
            return (sizeof($responsiveslideshows) > 0 ? $responsiveslideshows : 0);
        }
        return $responsiveslideshows;
    }
}
?>