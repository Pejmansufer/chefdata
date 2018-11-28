<?php

$installer = $this;

/* SM FormFactor */
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "status_sm", "smallint(6) NOT NULL default '0'"
);
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "popupviews_sm", "INT(11) NOT NULL DEFAULT '0'"
);
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "popupconversions_sm", "INT(11) NOT NULL DEFAULT '0'"
);
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "filename_sm", "varchar(255) NOT NULL DEFAULT ''"
);
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "background_color_sm", "varchar(255) NOT NULL DEFAULT ''"
);
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "height_sm", "varchar(255) NOT NULL DEFAULT ''"
);
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "width_sm", "varchar(255) NOT NULL DEFAULT ''"
);
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "promotionalpopup_content_sm", "text NOT NULL default ''"
);
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "styles_sm", "text NOT NULL default ''"
);

/* MD FormFactor */
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "status_md", "smallint(6) NOT NULL default '0'"
);
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "popupviews_md", "INT(11) NOT NULL DEFAULT '0'"
);
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "popupconversions_md", "INT(11) NOT NULL DEFAULT '0'"
);
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "filename_md", "varchar(255) NOT NULL DEFAULT ''"
);
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "background_color_md", "varchar(255) NOT NULL DEFAULT ''"
);
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "height_md", "varchar(255) NOT NULL DEFAULT ''"
);
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "width_md", "varchar(255) NOT NULL DEFAULT ''"
);
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "promotionalpopup_content_md", "text NOT NULL default ''"
);
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "styles_md", "text NOT NULL default ''"
);

// Total Conversions & Views for all form factors
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "popupviews_total", "INT(11) NOT NULL DEFAULT '0'"
);
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "popupconversions_total", "INT(11) NOT NULL DEFAULT '0'"
);

// Popup Group
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "popupgroup_id", "INT(11) NOT NULL DEFAULT '0'"
);

// CSS  reset status & template updates
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "css_reset", "smallint(6) NOT NULL default '1'"
);

$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "filename_close_btn", "varchar(255) NOT NULL DEFAULT ''"
);

$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "close_btn_label", "varchar(255) NOT NULL DEFAULT ''"
);

$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "close_label_color", "varchar(255) NOT NULL DEFAULT ''"
);

$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "close_btn_position", "varchar(255) NOT NULL DEFAULT ''"
);

$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "close_btn_vertical_offset", "INT(11) NOT NULL DEFAULT '0'"
);
$installer->getConnection()->addColumn(
    $installer->getTable("promotionalpopup"), "close_btn_horizontal_offset", "INT(11) NOT NULL DEFAULT '0'"
);


