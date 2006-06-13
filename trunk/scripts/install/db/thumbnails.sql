CREATE TABLE `%PREFIX%thumbnails` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `width` smallint(6) unsigned NOT NULL default '0',
  `height` smallint(6) unsigned NOT NULL default '0',
  `value_id` int(11) unsigned NOT NULL default '0',
  `type` smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `value_id` (`value_id`)
) TYPE=MyISAM;