CREATE TABLE `%PREFIX%vars` (
  `id` int(11) NOT NULL auto_increment,
  `class_name` varchar(100) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `priority` int(11) NOT NULL default '0',
  `type` varchar(50) NOT NULL default '',
  `extra` tinytext NOT NULL,
  `comment` tinytext NOT NULL,
  `required` tinyint(1) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM ;