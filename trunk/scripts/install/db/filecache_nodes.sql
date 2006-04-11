CREATE TABLE `%PREFIX%filecache_nodes` (
  `id` int(11) NOT NULL auto_increment,
  `filecache_id` int(11) NOT NULL default '0',
  `node_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM ;