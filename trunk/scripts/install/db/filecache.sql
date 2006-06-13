CREATE TABLE `%PREFIX%filecache` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `language` char(3) NOT NULL default '',
  `name` varchar(20) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `lifetime` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `language` (`language`),
  KEY `name` (`name`)
) TYPE=MyISAM;