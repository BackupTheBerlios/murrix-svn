CREATE TABLE `%PREFIX%groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `description` tinytext NOT NULL,
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;