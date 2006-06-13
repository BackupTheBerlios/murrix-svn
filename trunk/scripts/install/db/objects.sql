CREATE TABLE `%PREFIX%objects` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `node_id` int(11) unsigned NOT NULL default '0',
  `user_id` int(11) unsigned NOT NULL default '0',
  `group_id` int(11) unsigned NOT NULL default '0',
  `rights` varchar(9) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `class_name` varchar(20) NOT NULL default '',
  `version` smallint(6) unsigned NOT NULL default '0',
  `language` char(3) NOT NULL default '',
  `icon` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `node_id` (`node_id`),
  KEY `name` (`name`),
  KEY `class_name` (`class_name`),
  KEY `version` (`version`),
  KEY `language` (`language`)
) TYPE=MyISAM;