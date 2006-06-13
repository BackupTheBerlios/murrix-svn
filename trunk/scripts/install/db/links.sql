CREATE TABLE `%PREFIX%links` (
  `node_top` int(11) unsigned NOT NULL default '0',
  `node_bottom` int(11) unsigned NOT NULL default '0',
  `type` varchar(10) NOT NULL default '',
  `id` int(11) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`),
  KEY `node_top` (`node_top`),
  KEY `node_bottom` (`node_bottom`),
  KEY `type` (`type`)
) TYPE=MyISAM;