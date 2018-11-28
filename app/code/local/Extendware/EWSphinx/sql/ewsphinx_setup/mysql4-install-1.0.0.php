<?php
Mage::helper('ewcore/cache')->clean();
$installer = $this;
$installer->startSetup();

$command = "
DROP TABLE IF EXISTS `ewsphinx_stopword`;
CREATE TABLE `ewsphinx_stopword` (
  `stopword_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` smallint(5) unsigned NOT NULL,
  `word` varchar(255) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`stopword_id`),
  UNIQUE KEY `idx_word` (`word`,`store_id`) USING BTREE,
  KEY `idx_store_id` (`store_id`),
  CONSTRAINT `fk_jchm8g7v5qy9i78` FOREIGN KEY (`store_id`) REFERENCES `core_store` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		
DROP TABLE IF EXISTS `ewsphinx_synonym`;
CREATE TABLE `ewsphinx_synonym` (
  `synonym_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` smallint(5) unsigned NOT NULL,
  `word` varchar(255) NOT NULL,
  `synonyms` text NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`synonym_id`),
  UNIQUE KEY `idx_word` (`word`,`store_id`) USING BTREE,
  KEY `idx_store_id` (`store_id`),
  CONSTRAINT `fk_zqc2969awv8jwf2` FOREIGN KEY (`store_id`) REFERENCES `core_store` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";

$command = @preg_replace('/(EXISTS\s+`)([a-z0-9\_]+?)(`)/ie', '"\\1" . $this->getTable("\\2") . "\\3"', $command);
$command = @preg_replace('/(ON\s+`)([a-z0-9\_]+?)(`)/ie', '"\\1" . $this->getTable("\\2") . "\\3"', $command);
$command = @preg_replace('/(REFERENCES\s+`)([a-z0-9\_]+?)(`)/ie', '"\\1" . $this->getTable("\\2") . "\\3"', $command);
$command = @preg_replace('/(TABLE\s+`)([a-z0-9\_]+?)(`)/ie', '"\\1" . $this->getTable("\\2") . "\\3"', $command);
$command = @preg_replace('/(INTO\s+`)([a-z0-9\_]+?)(`)/ie', '"\\1" . $this->getTable("\\2") . "\\3"', $command);
$command = @preg_replace('/(FROM\s+`)([a-z0-9\_]+?)(`)/ie', '"\\1" . $this->getTable("\\2") . "\\3"', $command);

if ($command) $installer->run($command);
$installer->endSetup(); 

// ensure that the demo server starts off indexed
try {
	if (Mage::helper('ewcore/environment')->isDemoServer() === true) {
		Mage::getSingleton('ewsphinx/sphinx')->reindex();
	}
} catch (Exception $e) {
	Mage::logException($e);
}