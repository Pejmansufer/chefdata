<?php

$installer = $this;

$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "customergroup_ids", "varchar(255) NOT NULL DEFAULT ''"
);