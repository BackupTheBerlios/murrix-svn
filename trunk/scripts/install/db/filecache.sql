CREATE TABLE `%PREFIX%filecache` (
  `id` int(11) NOT NULL auto_increment,
  `language` varchar(10) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `lifetime` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM ;