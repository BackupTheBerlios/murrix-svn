CREATE TABLE `%PREFIX%nodes` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `rights` varchar(100) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;