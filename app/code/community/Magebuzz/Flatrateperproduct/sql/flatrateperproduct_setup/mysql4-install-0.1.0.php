<?php

$installer = $this;

$installer->startSetup();

$installer->addAttribute('catalog_product', 'ship_cost', array(
        'group'             => 'General',
        'type'              => 'decimal',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'Ship cost',
        'input'             => 'text',
        'class'             => '',
        'source'            => '',
        'is_global', Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible'           => true,
        'required'          => false,
        'user_defined'      => false,
        'default'           => '0',
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => false,
        'unique'            => false,
        'apply_to'          => 'simple,configurable,virtual,bundle,downloadable',
        'is_configurable'   => false,
        'used_in_product_listing', '1'
    ));
$installer->updateAttribute('catalog_product', 'ship_cost', 'used_in_product_listing', '1');

$installer->endSetup(); 