<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


class Amasty_Acart_Block_Quote_Items extends Mage_Catalog_Block_Product_Abstract
{
    protected $_params = array(
        'mode' => array(
            'default' => 'table',
            'available' => array(
                'list', 'table'
            )
        ),
        'image' => array(
            'default' => 'yes',
            'available' => array(
                'yes', 'no'
            )
        ),
        'imageSize' => array(
            'default' => '135'
        ),
        'showConfigurableImage' => array(
            'default' => 'no',
            'available' => array(
                'yes', 'no'
            )
        ),
        'price' => array(
            'default' => 'yes',
            'available' => array(
                'yes', 'no'
            )
        ),
        'priceFormat' => array(
            'default' => 'excludeTax',
            'available' => array(
                'exculdeTax', 'excludeTax', 'includeTax'
            )
        ),
        'descriptionFormat' => array(
            'default' => 'short',
            'available' => array(
                'short', 'full'
            )
        ),
        'discount' => array(
            'default' => 'yes',
            'available' => array(
                'yes', 'no'
            )
        ),
        'optionList' => array(
            'default' => 'no',
            'available' => array(
                'yes', 'no'
            )
        ),
    );

    public function __construct()
    {
        parent::__construct();

        $this->setTemplate('amacart/items.phtml');
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @param array $additional
     * @return mixed
     */
    public function getProductUrl($product, $additional = array())
    {
        if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE) {
            $parentIds = Mage::getModel('catalog/product_type_grouped')->getParentIdsByChild($product->getId());

            if ($parentIds && isset($parentIds[0])) {
                $parentUrlTypes = array(
                    Mage_Catalog_Model_Product_Type::TYPE_GROUPED,
                    Mage_Catalog_Model_Product_Type::TYPE_BUNDLE
                );
                $parent = Mage::getModel('catalog/product')->load($parentIds[0]);

                if (in_array($parent->getTypeId(), $parentUrlTypes)) {
                    $product = $parent;
                }
            }
        }

        $targetUrl = parent::getProductUrl($product, $additional);

        return Mage::getModel('amacart/urlmanager')
            ->init($this->getHistory())
            ->get($targetUrl);
    }

    protected function _getLayoutParam($key)
    {
        return !isset($this->_params[$key]['available']) || in_array($this->$key, $this->_params[$key]['available'])
            ? $this->$key
            : $this->_params[$key]['default'];
    }

    public function getMode()
    {
        return $this->_getLayoutParam('mode');
    }

    public function showImage()
    {
        return $this->_getLayoutParam('image') == 'yes';
    }

    public function showPrice()
    {
        return $this->_getLayoutParam('price') == 'yes';
    }

    public function showShortDescription()
    {
        return $this->_getLayoutParam('descriptionFormat') == 'short';
    }

    public function showPriceIncTax()
    {
        return $this->_getLayoutParam('priceFormat') == 'includeTax';
    }

    public function showDiscount()
    {
        return $this->_getLayoutParam('discount') == 'yes';
    }

    public function showOptionList()
    {
        return $this->_getLayoutParam('optionList') == 'yes';
    }

    public function showConfigurableImage()
    {
        return $this->_getLayoutParam('showConfigurableImage') == 'yes';
    }

    public function getImageSize()
    {
        $value = +$this->_getLayoutParam('imageSize');

        return $value ? $value : $this->_params['imageSize']['default'];
    }

    /**
     * @param $price
     * @return float|int
     */
    public function getDiscountPrice($price)
    {
        $discountPrice = $price;

        $scheduleId = $this->getHistory()->getScheduleId();
        $schedule = Mage::getModel('amacart/schedule')->load($scheduleId);

        switch ($schedule->getCouponType()) {
            case "by_percent":

                $discountPrice -= $discountPrice * $schedule->getDiscountAmount() / 100;
                break;
            case "by_fixed":
                $discountPrice -= $schedule->getDiscountAmount();
                break;
        }

        $discountPrice = $discountPrice < 0 ? 0 : $discountPrice;

        return $discountPrice;
    }

    public function getOptionList($item)
    {
        $product = $item->getProduct();
        $typeId = $product->getTypeId();
        $options = array();

        try {
            if ($typeId != Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE) {
                $helper = Mage::helper('catalog/product_configuration');
                $options = $helper->getCustomOptions($item);
            } else {
                $helper = Mage::helper('catalog/product_configuration');
                if (method_exists($helper, 'getConfigurableOptions')) {
                    $options = $helper->getConfigurableOptions($item);
                }
            }
        } catch (Exception $e) {
        }

        return $options;
    }

    public function getFormatedOptionValue($optionValue)
    {
        /* @var $helper Mage_Catalog_Helper_Product_Configuration */
        $helper = Mage::helper('catalog/product_configuration');
        $params = array(
            'max_length'   => 55,
            'cut_replacer' => ' <a href="#" class="dots" onclick="return false">...</a>'
        );
        return $helper->getFormattedOptionValue($optionValue, $params);
    }

    public function getItems()
    {
        $ret = array();

        if ($this->getQuote()) {
            $hasParentArr = array();

            if (!$this->showConfigurableImage()) {
                foreach ($this->getQuote()->getAllItems() as $item) {
                    if ($item->getParentItemId()) {
                        $hasParentArr[$item->getParentItemId()] = $item;
                    }
                }
            }

            foreach ($this->getQuote()->getAllVisibleItems() as $item) {
                $simpleProduct = null;
                $product = Mage::getModel('catalog/product')
                    ->setStoreId($this->getQuote()->getStoreId())
                    ->load($item->getProductId());
                $item->setProduct($product);
                if (isset($hasParentArr[$item->getId()])) {
                    $simpleProduct = Mage::getModel('catalog/product')
                        ->setStoreId($this->getQuote()->getStoreId())
                        ->load($hasParentArr[$item->getId()]->getProductId());
                }

                if(!$simpleProduct || !$simpleProduct->getSmallImage()) {
                    $productToUseForImage = $product;
                } else {
                    $productToUseForImage = $simpleProduct;
                }

                $img = $this->helper('catalog/image')->init($productToUseForImage, 'small_image')
                    ->resize($this->getImageSize());

                $ret[] = array(
                    'item'    => $item,
                    'product' => $product,
                    'image'   => $img->__toString()
                );
            }
        }

        return $ret;
    }
}
