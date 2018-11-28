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
 
$installer = $this;
$installer->startSetup();

$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('reviewemail/exclude')};
	CREATE TABLE `{$this->getTable('reviewemail/exclude')}` (
	  `exclude_id` mediumint(8) unsigned NOT NULL auto_increment,
	  `store_id` smallint UNSIGNED NOT NULL ,
	  `email` varchar(128) NOT NULL,
	  `date` datetime NOT NULL,
	  `status` smallint UNSIGNED NOT NULL,	  
	  KEY `email` (`store_id`, `email`),
	  PRIMARY KEY  (`exclude_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	ALTER TABLE {$this->getTable('reviewemail')} ADD `email_id` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `reviewemail_id`;

");

$installer->endSetup();
