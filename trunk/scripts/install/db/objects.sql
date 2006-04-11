CREATE TABLE `%PREFIX%objects` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `node_id` int(11) NOT NULL default '0',
  `creator` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `class_name` varchar(100) NOT NULL default '',
  `version` int(11) NOT NULL default '0',
  `language` char(3) NOT NULL default '',
  `icon` varchar(100) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM ;