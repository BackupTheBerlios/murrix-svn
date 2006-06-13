CREATE TABLE `%PREFIX%meta` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `node_id` int(11) unsigned NOT NULL default '0',
  `name` varchar(20) NOT NULL default '',
  `value` tinytext NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `node_id` (`node_id`),
  KEY `name` (`name`)
) TYPE=MyISAM;