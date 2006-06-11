CREATE TABLE `%PREFIX%settings` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `value` varchar(100) NOT NULL default '',
  `theme` varchar(100) NOT NULL default '',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;