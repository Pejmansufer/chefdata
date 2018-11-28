<?php
    /**
    * Copyright © Pulsestorm LLC: All rights reserved
    */
	$installer = $this;
	$installer->startSetup();
	try
	{
        $installer->run("
            CREATE TABLE `{$installer->getTable('commercebug/snapshot')}` (
              `snapshot_id` int(11) NOT NULL auto_increment,
              `snapshot_name_id` int not null,
              `hash` varchar(32),
              `file` text,
              `contents` mediumtext,
              PRIMARY KEY  (`snapshot_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
        
        $installer->run("
            CREATE TABLE `{$installer->getTable('commercebug/snapshot_name')}` (
              `snapshot_name_id` int(11) NOT NULL auto_increment,
              `snapshot_name` varchar(255) NOT NULL,
              PRIMARY KEY  (`snapshot_name_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
	}
	catch (Exception $e)
	{
	    //these tables were part of an older feature that
	    //never made it into the system.  Wrapping to catch
	    //a PDO exception if this extension is deployed somewhere
	}
	$installer->endSetup();