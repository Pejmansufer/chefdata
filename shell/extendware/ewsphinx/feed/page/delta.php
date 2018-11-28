<?php
$paths = array(
    dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/app/Mage.php',
	'../../../../../app/Mage.php',
	'../../../../app/Mage.php',
    '../../../app/Mage.php',
    '../../app/Mage.php',
    '../app/Mage.php',
    'app/Mage.php',
);

foreach ($paths as $path) {
    if (file_exists($path)) {
        require $path; 
        break;
    }
}

Mage::app('admin')->setUseSessionInUrl(false);
error_reporting(E_ALL | E_STRICT);
if (file_exists(BP.DS.'maintenance.flag')) exit;
if (class_exists('Extendware') === false) exit;
if (Extendware::helper('ewsphinx') === false) exit;
if (!isset($argv) or !is_array($argv)) $argv = array();

if (isset($_SERVER['REQUEST_METHOD'])) {
	$hash = sha1((string)Mage::getConfig()->getNode()->global->crypt->key);
	if (isset($_GET['hash']) and $_GET['hash'] != $hash and isset($argv[1]) and $argv[1] != $hash) {
		echo Mage::helper('ewsphinx')->__('Permission denied');
		exit;
	}
}

$storeId = @(isset($_GET['store_id']) ? $_GET['store_id'] : $argv[1]);
if (!$storeId) {
	echo Mage::helper('ewsphinx')->__('You must specify a store ID');
	exit;
}

$product = Mage::getModel('ewsphinx/feed_page');
$product->outputFeed($storeId, true);
