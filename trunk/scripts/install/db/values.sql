CREATE TABLE `%PREFIX%values` (
  `id` int(11) NOT NULL auto_increment,
  `data` text NOT NULL,
  `object_id` int(11) NOT NULL default '0',
  `var_id` int(11) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM ;