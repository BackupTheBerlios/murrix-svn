CREATE TABLE `%PREFIX%filecache_nodes` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `filecache_id` int(11) unsigned NOT NULL default '0',
  `node_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `filecache_id` (`filecache_id`),
  KEY `node_id` (`node_id`)
) TYPE=MyISAM;