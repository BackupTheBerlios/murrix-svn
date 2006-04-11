CREATE TABLE `%PREFIX%meta` (
  `id` int(11) NOT NULL auto_increment,
  `node_id` int(11) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `value` tinytext NOT NULL,
  KEY `id` (`id`)
) TYPE=MyISAM ;