CREATE TABLE `%PREFIX%links` (
  `node_top` int(11) NOT NULL default '0',
  `node_bottom` int(11) NOT NULL default '0',
  `type` varchar(50) NOT NULL default '',
  `id` int(11) NOT NULL auto_increment,
  KEY `id` (`id`)
) TYPE=MyISAM ;