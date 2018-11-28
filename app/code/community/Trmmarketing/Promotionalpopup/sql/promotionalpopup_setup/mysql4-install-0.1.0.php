<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */
$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('promotionalpopup')};
CREATE TABLE {$this->getTable('promotionalpopup')} (
  `promotionalpopup_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `background_color` varchar(255) NOT NULL default '',
  `template` varchar(255) NOT NULL default '',
  `cookie_value` varchar(255) NOT NULL default '',
  `cookie_expiry` int(11) NULL,
  `width` varchar(255) NOT NULL default '',
  `height` varchar(255) NOT NULL default '',
  `promotionalpopup_content` text NOT NULL default '',
  `styles` text NOT NULL default '',
  `delay` varchar(255) NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `stores_id` varchar(255) NOT NULL default '0',
  `timestatus` smallint(6) NOT NULL default '0',
  `coupon_id` varchar(255) NOT NULL default '',
  `begin_time` datetime NULL,
  `begin_hour` time NULL,
  `end_time` datetime NULL,
  `end_hour` time NULL,
  `sort_order` int(11) NULL,
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`promotionalpopup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 