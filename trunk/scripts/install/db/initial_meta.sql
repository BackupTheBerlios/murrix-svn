CREATE TABLE `%PREFIX%initial_meta` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `class_name` varchar(20) NOT NULL default '',
  `name` varchar(50) NOT NULL default '',
  `value` tinytext NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `class_name` (`class_name`),
  KEY `name` (`name`)
) TYPE=MyISAM;