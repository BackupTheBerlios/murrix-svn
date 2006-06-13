CREATE TABLE `%PREFIX%users` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `username` varchar(100) NOT NULL default '',
  `password` varchar(100) NOT NULL default '',
  `home_id` int(11) NOT NULL default '0',
  `groups` varchar(100) NOT NULL default '',
  `last_login` datetime NOT NULL default '0000-00-00 00:00:00',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;