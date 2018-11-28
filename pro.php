<?php 
require_once 'app/Mage.php';
umask(0);
Mage::app('default');
 

$manufacturerId = 5;
$attributeCode = 'manufacturer';
 
$collection = Mage::getModel('catalog/product')
                    ->getCollection()
                    ->addAttributeToFilter($attributeCode, $manufacturerId);


echo count($collection);
	
?>