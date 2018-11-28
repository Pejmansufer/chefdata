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

$installer = $this;
$installer->startSetup();

try {
    $installer->run("
        CREATE TABLE IF NOT EXISTS `{$this->getTable('awautorelated/blocks')}` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `type` TINYINT NOT NULL,
            `name` TINYTEXT NOT NULL,
            `status` TINYINT NOT NULL DEFAULT '1',
            `store` TEXT NOT NULL,
            `customer_groups` TEXT NOT NULL,
            `priority` INT NOT NULL DEFAULT '1',
            `date_from` DATE NULL,
            `date_to` DATE NULL,
            `position` INT NOT NULL,
            `currently_viewed` MEDIUMTEXT NOT NULL,
            `related_products` MEDIUMTEXT NOT NULL
        ) ENGINE = MyISAM DEFAULT CHARSET=utf8;
    ");

    $priceAttribute = Mage::getResourceSingleton('catalog/product')->getAttribute('price');
    if ($priceAttribute->isAllowedForRuleCondition() && $priceAttribute->getIsUsedForPromoRules()) {
        $installer->run("
            INSERT IGNORE INTO {$this->getTable('awautorelated/blocks')} (`type`, `name`, `status`, `store`, `customer_groups`, `priority`, `position`, `currently_viewed`, `related_products`) VALUES
            (" . AW_Autorelated_Model_Source_Type::PRODUCT_PAGE_BLOCK . ", \"Same category, lower price\", " . AW_Autorelated_Model_Source_Status::DISABLED . ", \"0\",
                \"". Mage_Customer_Model_Group::CUST_GROUP_ALL . "\", 0, " . AW_Autorelated_Model_Source_Position::INSTEAD_NATIVE_RELATED_BLOCK . ",
                'a:1:{s:10:\"conditions\";a:4:{s:4:\"type\";s:48:\"awautorelated/catalogrule_rule_condition_combine\";s:10:\"aggregator\";s:3:\"all\";s:5:\"value\";s:1:\"1\";s:9:\"new_child\";s:0:\"\";}}',
                'a:5:{s:7:\"general\";a:2:{i:0;a:2:{s:3:\"att\";s:12:\"category_ids\";s:9:\"condition\";s:1:\"=\";}i:1;a:2:{s:3:\"att\";s:5:\"price\";s:9:\"condition\";s:1:\"<\";}}s:7:\"related\";a:3:{s:5:\"order\";a:3:{s:4:\"type\";s:1:\"0\";s:9:\"attribute\";s:18:\"custom_design_from\";s:9:\"direction\";s:3:\"ASC\";}s:17:\"show_out_of_stock\";s:1:\"1\";s:10:\"conditions\";a:4:{s:4:\"type\";s:48:\"awautorelated/catalogrule_rule_condition_combine\";s:10:\"aggregator\";s:3:\"all\";s:5:\"value\";s:1:\"1\";s:9:\"new_child\";s:0:\"\";}}s:11:\"product_qty\";s:1:\"5\";s:17:\"show_out_of_stock\";s:1:\"1\";s:5:\"order\";a:3:{s:4:\"type\";s:1:\"0\";s:9:\"attribute\";s:18:\"custom_design_from\";s:9:\"direction\";s:3:\"ASC\";}}'),
            (" . AW_Autorelated_Model_Source_Type::CATEGORY_PAGE_BLOCK . ", \"Products under $30\", " . AW_Autorelated_Model_Source_Status::DISABLED . ", \"0\",
                \"". Mage_Customer_Model_Group::CUST_GROUP_ALL . "\", 0, " . AW_Autorelated_Model_Source_Position::BEFORE_CONTENT . ",
                'a:2:{s:4:\"area\";s:1:\"1\";s:12:\"category_ids\";s:0:\"\";}',
                'a:5:{s:7:\"include\";s:1:\"1\";s:5:\"count\";s:2:\"10\";s:5:\"order\";a:3:{s:4:\"type\";s:1:\"0\";s:9:\"attribute\";s:18:\"custom_design_from\";s:9:\"direction\";s:3:\"ASC\";}s:17:\"show_out_of_stock\";s:1:\"0\";s:10:\"conditions\";a:5:{s:4:\"type\";s:48:\"awautorelated/catalogrule_rule_condition_combine\";s:10:\"aggregator\";s:3:\"all\";s:5:\"value\";s:1:\"1\";s:9:\"new_child\";s:0:\"\";s:7:\"related\";a:1:{i:1;a:4:{s:4:\"type\";s:48:\"awautorelated/catalogrule_rule_condition_product\";s:9:\"attribute\";s:5:\"price\";s:8:\"operator\";s:1:\"<\";s:5:\"value\";s:2:\"30\";}}}}');
        ");
    }


} catch (Exception $ex) {
    Mage::logException($ex);
}
$installer->endSetup();