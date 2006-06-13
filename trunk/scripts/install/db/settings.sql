CREATE TABLE `%PREFIX%settings` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL default '',
  `value` varchar(100) NOT NULL default '',
  `theme` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `theme` (`theme`)
) TYPE=MyISAM;