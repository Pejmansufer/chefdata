<?php

class Webtex_FeaturedProductsSlider_Adminhtml_Widget_InstanceController extends Mage_Adminhtml_Controller_Action
{
    public function productsproAction()
    {
        $selected = $this->getRequest()->getParam('selected', '');
        $productTypeId = $this->getRequest()->getParam('product_type_id', '');
        $category = $this->getRequest()->getParam('category', '');
        $categoryId = str_replace('category/', '', $category);
        $chooser = $this->getLayout()
            ->createBlock('featuredproductsslider/adminhtml_catalog_product_widget_chooser')
            ->setName(Mage::helper('core')->uniqHash('products_grid_'))
            ->setUseMassaction(true)
            ->setProductTypeId($productTypeId)
            ->setCategoryId($categoryId)
            ->setSelectedProducts(explode(',', $selected));
        /* @var $serializer Mage_Adminhtml_Block_Widget_Grid_Serializer */
        $serializer = $this->getLayout()->createBlock('adminhtml/widget_grid_serializer');
        $serializer->initSerializerBlock($chooser, 'getSelectedProducts', 'selected_products', 'selected_products');
        $this->getResponse()->setBody($chooser->toHtml().$serializer->toHtml());
    }
}
