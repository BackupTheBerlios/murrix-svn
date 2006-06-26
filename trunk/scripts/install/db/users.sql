CREATE TABLE `%PREFIX%users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `username` varchar(20) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `password` varchar(100) NOT NULL default '',
  `home_id` int(11) unsigned NOT NULL default '0',
  `groups` varchar(100) NOT NULL default '',
  `last_login` datetime NOT NULL default '0000-00-00 00:00:00',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `username` (`username`)
) TYPE=MyISAM;