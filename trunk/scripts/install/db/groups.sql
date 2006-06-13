CREATE TABLE `%PREFIX%groups` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL default '',
  `description` tinytext NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) TYPE=MyISAM;