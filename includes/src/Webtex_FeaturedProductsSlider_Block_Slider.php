<?php

class Webtex_FeaturedProductsSlider_Block_Slider
    extends Mage_Catalog_Block_Product_Abstract
    implements Mage_Widget_Block_Interface
{
    protected function _construct()
    {
        parent::_construct();
        $this->setProductsViewCount(3);
    }

    protected function checkDates()
    {
        if ($this->getData('date_from') || $this->getData('date_to')) {
            $from = new DateTime($this->getData('date_from'));
            $to = new DateTime($this->getData('date_to'));
            $now = new DateTime();
            return !(($this->getData('date_from') && $now < $from) || ($this->getData('date_to') && $now > $to));
        }
        return true;
    }

    protected function checkCountry()
    {
        if ($this->getData('countries')) {
            $geoIp = Mage::helper('featuredproductsslider')->getGeoIP();
            $currentCountry = $geoIp->getCode();
            return in_array($currentCountry, explode(',', $this->getData('countries')));
        }
        return true;
    }

    protected function getProductCollection()
    {
        $products = explode(',', $this->getData('products'));
        $storeId = Mage::app()->getStore()->getId();
        if (in_array('specific', $products)) {
            $products = array_diff($products, array('specific'));
            $collection = Mage::getModel('catalog/product')
                ->getCollection()
                ->addIdFilter($products);
        } elseif (in_array('random', $products)) {
            $collection = Mage::getModel('catalog/product')
                ->getCollection();
            $collection->getSelect()->order('rand()');
        } elseif (in_array('bestsellers', $products)) {
            $collection = Mage::getResourceModel('reports/product_collection')
                ->addOrderedQty()
                ->setOrder('ordered_qty', 'desc');
        } elseif (in_array('new', $products)) {
            $todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
            $collection = Mage::getModel('catalog/product')
                ->getCollection()
                ->addAttributeToFilter('news_from_date', array('or'=> array(
                    0 => array('date' => true, 'to' => $todayDate),
                    1 => array('is' => new Zend_Db_Expr('null')))
                ), 'left')
                ->addAttributeToFilter('news_to_date', array('or'=> array(
                    0 => array('date' => true, 'from' => $todayDate),
                    1 => array('is' => new Zend_Db_Expr('null')))
                ), 'left')
                ->addAttributeToFilter(
                    array(
                        array('attribute' => 'news_from_date', 'is'=>new Zend_Db_Expr('not null')),
                        array('attribute' => 'news_to_date', 'is'=>new Zend_Db_Expr('not null'))
                    )
                )
                ->addAttributeToSort('news_from_date', 'desc');
        } elseif (in_array('most_viewed', $products)) {
            $collection = Mage::getResourceModel('reports/product_collection')
                ->addViewsCount()
                ->addAttributeToSort('review_cnt', 'desc');
        } elseif (in_array('most_reviewed', $products)) {
            $collection = Mage::getResourceModel('reports/review_product_collection')
                ->joinReview()
                ->addAttributeToSort('review_cnt', 'desc');
        }

        if ($collection) {
            $collection
                ->setStoreId($storeId)
                ->addStoreFilter($storeId)
                ->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());
            $categoryId = str_replace('category/', '', $this->getData('category'));
            if ($categoryId && $categoryId != Mage::app()->getStore()->getRootCategoryId()) {
                $category = Mage::getModel('catalog/category')->load($categoryId);
                $collection->addCategoryFilter($category);
            }
            if ($this->getData('products_count')) {
                $collection->getSelect()->limit($this->getData('products_count'));
            }
            $collection = $this->_addProductAttributesAndPrices($collection);

            return $collection;
        }

        return new Varien_Data_Collection();
    }

    public function getDisplayOptionsArray()
    {
        return explode(',', $this->getData('display_options'));
    }

    public function isShowOption($attributeCode)
    {
        return in_array($attributeCode, $this->getDisplayOptionsArray());
    }

    protected function _addProductAttributesAndPrices(Mage_Catalog_Model_Resource_Product_Collection $collection)
    {
        return $collection
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addUrlRewrite();
    }

    public function getAddToCartUrl($product, $additional = array())
    {
        if ($product->getTypeInstance(true)->hasRequiredOptions($product)) {
            if (!isset($additional['_escape'])) {
                $additional['_escape'] = true;
            }
            if (!isset($additional['_query'])) {
                $additional['_query'] = array();
            }
            $additional['_query']['options'] = 'cart';

            return $this->getProductUrl($product, $additional);
        }
        return $this->helper('checkout/cart')->getAddUrl($product, $additional);
    }

    public function getProductUrl($product, $additional = array())
    {
        if ($this->hasProductUrl($product)) {
            if (!isset($additional['_escape'])) {
                $additional['_escape'] = true;
            }
            return $product->getUrlModel()->getUrl($product, $additional);
        }

        return '#';
    }
}