<?php

$installer = $this;

$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "closechainedpopup_id", "int(11) NULL"
);

$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "conversionchainedpopup_id", "int(11) NULL"
);