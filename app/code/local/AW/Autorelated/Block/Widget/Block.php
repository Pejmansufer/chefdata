<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This software is designed to work with Magento community edition and
 * its use on an edition other than specified is prohibited. aheadWorks does not
 * provide extension support in case of incorrect edition use.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Autorelated
 * @version    2.4.8
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

class AW_Autorelated_Block_Widget_Block extends AW_Autorelated_Block_Blocks implements Mage_Widget_Block_Interface
{
    public function getBlocksHtml()
    {
        $html = parent::getBlocksHtml();
        switch ($this->getBlocks()->getFirstItem()->getType()) {
            case AW_Autorelated_Model_Source_Type::PRODUCT_PAGE_BLOCK:
                $css = 'aw_autorelated/css/product.css';
                break;
            case AW_Autorelated_Model_Source_Type::CATEGORY_PAGE_BLOCK:
                $css = 'aw_autorelated/css/category.css';
                break;
            case AW_Autorelated_Model_Source_Type::SHOPPING_CART_BLOCK:
                $css = 'aw_autorelated/css/shoppingcart.css';
                break;
            default:
                $css = false;
                break;
        }
        if ($css) {
            $href = Mage::getDesign()->getSkinUrl($css, array());
            $script = "
                if ($$('head link[href=" . $href . "]').length == 0) {
                    $$('head').first().insert(new Element('link', {
                        'rel': 'stylesheet',
                        'type': 'text/css',
                        'href': '" . $href . "'
                    }));
                }
            ";
            $html .= '<script>' . $script . '</script>';
        }
        return $html;
    }
}