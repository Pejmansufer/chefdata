<?php
/**
 * WeaveApps_ResponsiveSlideshow Extension
 *
 * @category   WeaveApps
 * @package    WeaveApps_ResponsiveSlideshow
 * @copyright  Copyright (c) 2014 Weave Apps. (http://www.weaveapps.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *
 */
$installer = $this;
$installer->startSetup();
$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('responsive_slideshow')};
CREATE TABLE {$this->getTable('responsive_slideshow')} (
  `rs_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `video_id` varchar(255) NOT NULL default '',
  `video_height` varchar(255) NOT NULL default '',
  `auto_play` TINYINT(4) NOT NULL default '0',
  `link_target` TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '0=>New Window, 1=> Self',
  `caption_position` varchar(255) NOT NULL default 'none',
  `banner_type` TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '0=>Image, 1=>Video',
  `image` varchar(255) NOT NULL default '',
  `caption_font_colour` varchar(255) NOT NULL default '#000',
  `caption_bg_colour` varchar(255) NOT NULL default '#FFF',
  `show_caption` TINYINT(4) NOT NULL default '0',
  `caption` text NOT NULL default '',
  `sort_order` smallint(6) NOT NULL default '0',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`rs_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('responsive_slideshow_bannergroup')};
CREATE TABLE {$this->getTable('responsive_slideshow_bannergroup')} (
 `group_id` int(11) unsigned NOT NULL auto_increment,
 `group_name` varchar(255) NOT NULL default '',
 `transition_settings` varchar(255) NOT NULL default 'fade',
 `easing_settings` varchar(255) NOT NULL default 'easeInOutSine',
 `banner_ids` varchar(255) NOT NULL default '',
  `slider_interval` varchar(255) NOT NULL default '',
  `slider_speed` varchar(255) NOT NULL default '',
 `show_paging` TINYINT(4) NOT NULL default '0',
 `show_nav` TINYINT(4) NOT NULL default '0',
  `store_id` varchar(255) NOT NULL default '',
  `custom_odr` varchar(255) NOT NULL default '',
 `status` smallint(6) NOT NULL default '0',
 `created_time` DATETIME NULL,
 `update_time` DATETIME NULL,
 `start_time` DATETIME NULL,
 `end_time` DATETIME NULL,
 PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;









");