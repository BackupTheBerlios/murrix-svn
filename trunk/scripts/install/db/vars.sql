CREATE TABLE `%PREFIX%vars` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `class_name` varchar(20) NOT NULL default '',
  `name` varchar(20) NOT NULL default '',
  `priority` smallint(6) unsigned NOT NULL default '0',
  `type` varchar(20) NOT NULL default '',
  `extra` tinytext NOT NULL,
  `comment` tinytext NOT NULL,
  `required` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `class_name` (`class_name`)
) TYPE=MyISAM;