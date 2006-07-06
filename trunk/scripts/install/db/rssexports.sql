CREATE TABLE `%PREFIX%rssexports` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(20) NOT NULL default '',
  `admin` varchar(20) NOT NULL default '',
  `description` tinytext NOT NULL,
  `fetch` tinytext NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;