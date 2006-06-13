CREATE TABLE `%PREFIX%pathcache` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `path` tinytext NOT NULL,
  `node_id` int(11) unsigned NOT NULL default '0',
  `language` char(3) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `node_id` (`node_id`),
  KEY `language` (`language`)
) TYPE=MyISAM;