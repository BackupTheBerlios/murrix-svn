CREATE TABLE `%PREFIX%rssexports` (
  `id` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `admin` varchar(100) NOT NULL default '',
  `description` tinytext NOT NULL,
  `fetch` tinytext NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM ;