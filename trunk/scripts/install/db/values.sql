CREATE TABLE `%PREFIX%values` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `data` text NOT NULL,
  `object_id` int(11) unsigned NOT NULL default '0',
  `var_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `var_id` (`var_id`)
) TYPE=MyISAM;