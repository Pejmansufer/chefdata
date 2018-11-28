<?php

$installer = $this;

$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "popupviews", "INT(11) NOT NULL DEFAULT '0'"
);

$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "popupconversions", "INT(11) NOT NULL DEFAULT '0'"
);