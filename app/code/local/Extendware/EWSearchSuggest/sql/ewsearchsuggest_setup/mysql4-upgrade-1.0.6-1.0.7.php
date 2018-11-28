<?php

$installer = $this;
$installer->startSetup();

$command  = "
DROP TABLE IF EXISTS `ewsearchsuggest_fulltext_category`;
CREATE TABLE `ewsearchsuggest_fulltext_category` (
  `fulltext_category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` smallint(5) unsigned NOT NULL COMMENT 'Store ID',
  `category_id` int(10) unsigned NOT NULL COMMENT 'Product ID',
  `data_index` longtext COMMENT 'Data index',
  PRIMARY KEY (`fulltext_category_id`),
  UNIQUE KEY `idx_unique` (`category_id`,`store_id`) USING BTREE,
  FULLTEXT KEY `idx_data_index` (`data_index`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		
DROP TABLE IF EXISTS `ewsearchsuggest_fulltext_page`;
CREATE TABLE `ewsearchsuggest_fulltext_page` (
  `fulltext_page_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` smallint(5) unsigned NOT NULL COMMENT 'Store ID',
  `page_id` int(10) unsigned NOT NULL COMMENT 'Product ID',
  `data_index` longtext COMMENT 'Data index',
  PRIMARY KEY (`fulltext_page_id`),
  UNIQUE KEY `idx_unique` (`page_id`,`store_id`) USING BTREE,
  FULLTEXT KEY `idx_data_index` (`data_index`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
";

$command = @preg_replace('/(EXISTS\s+`)([a-z0-9\_]+?)(`)/ie', '"\\1" . $this->getTable("\\2") . "\\3"', $command);
$command = @preg_replace('/(ON\s+`)([a-z0-9\_]+?)(`)/ie', '"\\1" . $this->getTable("\\2") . "\\3"', $command);
$command = @preg_replace('/(REFERENCES\s+`)([a-z0-9\_]+?)(`)/ie', '"\\1" . $this->getTable("\\2") . "\\3"', $command);
$command = @preg_replace('/(TABLE\s+`)([a-z0-9\_]+?)(`)/ie', '"\\1" . $this->getTable("\\2") . "\\3"', $command);

$installer->run($command);
$installer->endSetup();

// initial indexing
if (Mage::helper('ewcore/environment')->isDemoServer() === true) {
	try {
		$processes = Mage::getModel('index/process')->getCollection();
		foreach ($processes as $process) {
			try {
			$process->reindexEverything();
			} catch (Exception $e) {}
		}
		Mage::getModel('ewsearchsuggest/indexer')->reindexAll();
	} catch (Exception $e) {}
}