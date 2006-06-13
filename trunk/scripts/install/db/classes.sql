CREATE TABLE `%PREFIX%classes` (
  `name` varchar(20) NOT NULL default '',
  `default_icon` varchar(20) NOT NULL default '',
  `id` int(11) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`name`),
  KEY `id` (`id`)
) TYPE=MyISAM;