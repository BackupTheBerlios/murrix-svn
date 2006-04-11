CREATE TABLE `%PREFIX%thumbnails` (
  `id` int(11) NOT NULL auto_increment,
  `data` blob NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `width` smallint(6) NOT NULL default '0',
  `height` smallint(6) NOT NULL default '0',
  `value_id` int(11) NOT NULL default '0',
  `type` smallint(6) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM ;