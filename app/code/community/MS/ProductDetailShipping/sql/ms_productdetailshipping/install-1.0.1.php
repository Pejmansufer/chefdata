<?php

$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('ms_productdetailshipping/zipcodes'))
    ->addColumn('zip', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Zip Code')
    ->addColumn('state', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'State')
    ->addColumn('country', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Country')
    ->setComment('Zip Codes Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();
