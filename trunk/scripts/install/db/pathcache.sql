CREATE TABLE `%PREFIX%pathcache` (
  `id` int(11) NOT NULL auto_increment,
  `path` tinytext NOT NULL,
  `node_id` int(11) NOT NULL default '0',
  `language` varchar(10) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM ;