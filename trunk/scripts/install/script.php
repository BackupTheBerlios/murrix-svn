<?

class sInstall extends Script
{
	function sInstall()
	{
		$this->admin_password = "";
		$this->admin_username = "admin";
		$this->db_address = "localhost";
		$this->db_name = "murrix";
		$this->db_prefix = "murrix_";
		$this->db_username = "";
		$this->db_password = "";
		$this->site = "standard";
	}
	
	function Exec(&$system, &$response, $args)
	{
		global $abspath, $wwwpath;
	
		if (!isset($args['stage']))
			$args['stage'] = 1;

		if (isset($args['admin_username']))
			$this->admin_username = $args['admin_username'];

		if (isset($args['admin_password1']) || isset($args['admin_password2']))
		{
			if ($args['admin_password1'] != $args['admin_password2'])
			{
				$response->addAlert("Passwords don't match, please try again.");
				return;
			}
			$this->admin_password = $args['admin_password1'];
		}

		if (isset($args['db_address']))
			$this->db_address = $args['db_address'];

		if (isset($args['db_name']))
			$this->db_name = $args['db_name'];

		if (isset($args['db_prefix']))
			$this->db_prefix = $args['db_prefix'];

		if (isset($args['db_username']))
			$this->db_username = $args['db_username'];

		if (isset($args['db_password']))
			$this->db_password = $args['db_password'];

		if (isset($args['site']))
			$this->site = $args['site'];

		if ($args['stage'] == 4)
		{
			if (empty($this->admin_username) || empty($this->admin_password))
			{
				$response->addAlert("Please fill in all fields.");
				return;
			}
		}
		else if ($args['stage'] == 5)
		{
			if (empty($this->db_address) || empty($this->db_name) || empty($this->db_username) || empty($this->db_password))
			{
				$response->addAlert("Please fill in all fields. Table prefix is optional.");
				return;
			}

			$this->db_log = "";

			if (!$db_conn = mysql_pconnect($this->db_address, $this->db_username, $this->db_password))
			{
				$this->db_login = false;
				$this->db_log .= "Error connecting to MySQL: " . mysql_errno() . " " . mysql_error()."<br/>";
			}
			else
			{
				$this->db_login = true;
				$this->db_tables = false;
				
				if (mysql_select_db($this->db_name))
				{
					$this->db_exists = true;
					$this->db_log .= "Database ".$this->db_name." exists.<br/>";

					$tables = array("classes", "links", "meta", "nodes", "objects", "pathcache", "thumbnails", "values", "vars");
					foreach ($tables as $table)
					{
						if (mysql_query("SELECT * FROM `".$this->db_prefix."$table"))
						{
							$this->db_log .= "Table ".$this->db_prefix."$table exists.<br/>";
							$this->db_tables = true;
						}
					}
				}
				else
				{
					$this->db_exists = false;
				}
			}

		}
		else if ($args['stage'] == 7)
		{
			$this->done = true;
			$this->db_log = "";
			
			if (!$db_conn = mysql_pconnect($this->db_address, $this->db_username, $this->db_password))
			{
				$this->done = false;
				$this->db_log .= "Error connecting to MySQL: " . mysql_errno() . " " . mysql_error()."<br/>";
			}

			if ($this->done)
			{
				if (!$this->db_exists)
				{
					if (mysql_query("CREATE DATABASE `".$this->db_name."`"))
						$this->db_log .= "Database ".$this->db_name." created.<br/>";
					else
					{
						$this->db_log .= "Failed to create database ".$this->db_name.". Error: " . mysql_errno() . " " . mysql_error()."<br/>";
						$this->done = false;
					}
				}

				mysql_select_db($this->db_name);
			}
			
			if ($this->done)
			{
				$query = "DROP TABLE IF EXISTS `".$this->db_prefix."classes`;";
				mysql_query($query);
				
				$query = "CREATE TABLE `".$this->db_prefix."classes` (";
				$query .= "`name` varchar(100) NOT NULL default '',";
				$query .= "`default_icon` varchar(100) NOT NULL default '',";
				$query .= "`id` int(11) NOT NULL auto_increment,";
				$query .= "KEY `id` (`id`)";
				$query .= ") TYPE=MyISAM;";
				
				if (mysql_query($query))
					$this->db_log .= "Table ".$this->db_prefix."classes created.<br/>";
				else
				{
					$this->db_log .= "Falied to create table ".$this->db_prefix."classes. Error: " . mysql_errno() . " " . mysql_error()."<br/>";
					$this->done = false;
				}
			}

			if ($this->done)
			{
				$query = "DROP TABLE IF EXISTS `".$this->db_prefix."links`;";
				mysql_query($query);
				
				$query = "CREATE TABLE `".$this->db_prefix."links` (";
				$query .= "`node_top` int(11) NOT NULL default '0',";
				$query .= "`node_bottom` int(11) NOT NULL default '0',";
				$query .= "`type` varchar(50) NOT NULL default '',";
				$query .= "`id` int(11) NOT NULL auto_increment,";
				$query .= "KEY `id` (`id`)";
				$query .= ") TYPE=MyISAM;";
			
				if (mysql_query($query))
					$this->db_log .= "Table ".$this->db_prefix."links created.<br/>";
				else
				{
					$this->db_log .= "Falied to create table ".$this->db_prefix."links. Error: " . mysql_errno() . " " . mysql_error()."<br/>";
					$this->done = false;
				}
			}
			
			if ($this->done)
			{
				$query = "DROP TABLE IF EXISTS `".$this->db_prefix."meta`;";
				mysql_query($query);
				
				$query = "CREATE TABLE `".$this->db_prefix."meta` (";
				$query .= "`id` int(11) NOT NULL auto_increment,";
				$query .= "`node_id` int(11) NOT NULL default '0',";
				$query .= "`name` varchar(100) NOT NULL default '',";
				$query .= "`value` tinytext NOT NULL,";
				$query .= "KEY `id` (`id`)";
				$query .= ") TYPE=MyISAM;";
				
				if (mysql_query($query))
					$this->db_log .= "Table ".$this->db_prefix."meta created.<br/>";
				else
				{
					$this->db_log .= "Falied to create table ".$this->db_prefix."meta. Error: " . mysql_errno() . " " . mysql_error()."<br/>";
					$this->done = false;
				}
			}

			if ($this->done)
			{
				$query = "DROP TABLE IF EXISTS `".$this->db_prefix."nodes`;";
				mysql_query($query);
				
				$query = "CREATE TABLE `".$this->db_prefix."nodes` (";
				$query .= "`id` int(11) NOT NULL auto_increment,";
				$query .= "`created` datetime NOT NULL default '0000-00-00 00:00:00',";
				$query .= "KEY `id` (`id`)";
				$query .= ") TYPE=MyISAM;";
				
				if (mysql_query($query))
					$this->db_log .= "Table ".$this->db_prefix."nodes created.<br/>";
				else
				{
					$this->db_log .= "Falied to create table ".$this->db_prefix."nodes. Error: " . mysql_errno() . " " . mysql_error()."<br/>";
					$this->done = false;
				}
			}
			
			if ($this->done)
			{
				$query = "DROP TABLE IF EXISTS `".$this->db_prefix."objects`;";
				mysql_query($query);
				
				$query = "CREATE TABLE `".$this->db_prefix."objects` (";
				$query .= "`id` int(11) NOT NULL auto_increment,";
				$query .= "`name` varchar(100) NOT NULL default '',";
				$query .= "`node_id` int(11) NOT NULL default '0',";
				$query .= "`creator` int(11) NOT NULL default '0',";
				$query .= "`created` datetime NOT NULL default '0000-00-00 00:00:00',";
				$query .= "`class_name` varchar(100) NOT NULL default '',";
				$query .= "`version` int(11) NOT NULL default '0',";
				$query .= "`language` char(3) NOT NULL default '',";
				$query .= "`icon` varchar(100) NOT NULL default '',";
				$query .= "KEY `id` (`id`)";
				$query .= ") TYPE=MyISAM;";
				
				if (mysql_query($query))
					$this->db_log .= "Table ".$this->db_prefix."objects created.<br/>";
				else
				{
					$this->db_log .= "Falied to create table ".$this->db_prefix."objects. Error: " . mysql_errno() . " " . mysql_error()."<br/>";
					$this->done = false;
				}
			}

			if ($this->done)
			{
				$query = "DROP TABLE IF EXISTS `".$this->db_prefix."pathcache`;";
				mysql_query($query);
				
				$query = "CREATE TABLE `".$this->db_prefix."pathcache` (";
				$query .= "`id` int(11) NOT NULL auto_increment,";
				$query .= "`path` tinytext NOT NULL,";
				$query .= "`node_id` int(11) NOT NULL default '0',";
				$query .= "KEY `id` (`id`)";
				$query .= ") TYPE=MyISAM;";
				
				if (mysql_query($query))
					$this->db_log .= "Table ".$this->db_prefix."pathcache created.<br/>";
				else
				{
					$this->db_log .= "Falied to create table ".$this->db_prefix."pathcache. Error: " . mysql_errno() . " " . mysql_error()."<br/>";
					$this->done = false;
				}
			}

			if ($this->done)
			{
				$query = "DROP TABLE IF EXISTS `".$this->db_prefix."thumbnails`;";
				mysql_query($query);
				
				$query = "CREATE TABLE `".$this->db_prefix."thumbnails` (";
				$query .= "`id` int(11) NOT NULL auto_increment,";
				$query .= "`data` blob NOT NULL,";
				$query .= "`created` datetime NOT NULL default '0000-00-00 00:00:00',";
				$query .= "`width` smallint(6) NOT NULL default '0',";
				$query .= "`height` smallint(6) NOT NULL default '0',";
				$query .= "`value_id` int(11) NOT NULL default '0',";
				$query .= "`type` smallint(6) NOT NULL default '0',";
				$query .= "KEY `id` (`id`)";
				$query .= ") TYPE=MyISAM;";
				
				if (mysql_query($query))
					$this->db_log .= "Table ".$this->db_prefix."thumbnails created.<br/>";
				else
				{
					$this->db_log .= "Falied to create table ".$this->db_prefix."thumbnails. Error: " . mysql_errno() . " " . mysql_error()."<br/>";
					$this->done = false;
				}
			}

			if ($this->done)
			{
				$query = "DROP TABLE IF EXISTS `".$this->db_prefix."values`;";
				mysql_query($query);
				
				$query = "CREATE TABLE `".$this->db_prefix."values` (";
				$query .= "`id` int(11) NOT NULL auto_increment,";
				$query .= "`data` text NOT NULL,";
				$query .= "`object_id` int(11) NOT NULL default '0',";
				$query .= "`var_id` int(11) NOT NULL default '0',";
				$query .= "KEY `id` (`id`)";
				$query .= ") TYPE=MyISAM;";
				
				if (mysql_query($query))
					$this->db_log .= "Table ".$this->db_prefix."values created.<br/>";
				else
				{
					$this->db_log .= "Falied to create table ".$this->db_prefix."values. Error: " . mysql_errno() . " " . mysql_error()."<br/>";
					$this->done = false;
				}
			}

			if ($this->done)
			{
				$query = "DROP TABLE IF EXISTS `".$this->db_prefix."vars`;";
				mysql_query($query);
				
				$query = "CREATE TABLE `".$this->db_prefix."vars` (";
				$query .= "`id` int(11) NOT NULL auto_increment,";
				$query .= "`class_name` varchar(100) NOT NULL default '',";
				$query .= "`name` varchar(100) NOT NULL default '',";
				$query .= "`priority` int(11) NOT NULL default '0',";
				$query .= "`type` varchar(50) NOT NULL default '',";
				$query .= "`extra` tinytext NOT NULL,";
				$query .= "KEY `id` (`id`)";
				$query .= ") TYPE=MyISAM;";
				
				if (mysql_query($query))
					$this->db_log .= "Table ".$this->db_prefix."vars created.<br/>";
				else
				{
					$this->db_log .= "Falied to create table ".$this->db_prefix."vars. Error: " . mysql_errno() . " " . mysql_error()."<br/>";
					$this->done = false;
				}
			}

			// Insert initial objects

			if ($this->done)
			{
				$query = "INSERT INTO `".$this->db_prefix."classes` (`name`, `default_icon`, `id`) VALUES ('folder', 'folder', 1),";
				$query .= "('user', 'user', 2),";
				$query .= "('group', 'group', 3),";
				$query .= "('article', 'article', 4),";
				$query .= "('link', 'global', 5),";
				$query .= "('news', 'news', 6),";
				$query .= "('event', 'date', 7),";
				$query .= "('right_read', 'password', 8),";
				$query .= "('right_delete', 'password', 9),";
				$query .= "('right_edit', 'password', 10),";
				$query .= "('right_create_subnodes', 'password', 11),";
				$query .= "('right_read_subnodes', 'password', 12),";
				$query .= "('comment', 'comment', 13),";
				$query .= "('forum_topic', 'comment', 14),";
				$query .= "('forum_post', 'comment', 15),";
				$query .= "('forum_thread', 'comment', 16),";
				$query .= "('file_folder', 'file_folder', 17),";
				$query .= "('file', 'file', 18);";
				
				if (mysql_query($query))
					$this->db_log .= "Inserted classes into ".$this->db_prefix."classes.<br/>";
				else
				{
					$this->db_log .= "Failed to insert vars classes ".$this->db_prefix."classes.<br/>";
					$this->done = false;
				}
			}

			if ($this->done)
			{
				$query = "INSERT INTO `".$this->db_prefix."links` (`node_top`, `node_bottom`, `type`, `id`) VALUES (1, 2, 'sub', 1),";
				$query .= "(1, 3, 'sub', 2),";
				$query .= "(2, 4, 'sub', 3),";
				$query .= "(2, 8, 'sub', 4),";
				$query .= "(4, 5, 'sub', 5),";
				$query .= "(4, 6, 'sub', 6),";
				$query .= "(4, 7, 'sub', 7),";
				$query .= "(8, 9, 'sub', 8),";
				$query .= "(8, 10, 'sub', 9),";
				$query .= "(8, 11, 'sub', 10),";
				$query .= "(8, 12, 'sub', 11),";
				$query .= "(8, 13, 'sub', 12),";
				$query .= "(8, 14, 'sub', 13);";
				
				if (mysql_query($query))
					$this->db_log .= "Inserted links into ".$this->db_prefix."links.<br/>";
				else
				{
					$this->db_log .= "Failed to insert links into ".$this->db_prefix."links.<br/>";
					$this->done = false;
				}
			}
			
			if ($this->done)
			{
				$datetime = date("Y-m-d H:i:s");
			
				$query = "INSERT INTO `".$this->db_prefix."nodes` (`id`, `created`) VALUES (1, '$datetime'),";
				$query .= "(2, '$datetime'),";
				$query .= "(3, '$datetime'),";
				$query .= "(4, '$datetime'),";
				$query .= "(5, '$datetime'),";
				$query .= "(6, '$datetime'),";
				$query .= "(7, '$datetime'),";
				$query .= "(8, '$datetime'),";
				$query .= "(9, '$datetime'),";
				$query .= "(10, '$datetime'),";
				$query .= "(11, '$datetime'),";
				$query .= "(12, '$datetime'),";
				$query .= "(13, '$datetime'),";
				$query .= "(14, '$datetime');";
				
				if (mysql_query($query))
					$this->db_log .= "Inserted nodes into ".$this->db_prefix."nodes.<br/>";
				else
				{
					$this->db_log .= "Failed to insert nodes into ".$this->db_prefix."nodes.<br/>";
					$this->done = false;
				}
			}

			if ($this->done)
			{
				$datetime = date("Y-m-d H:i:s");
			
				$query = "INSERT INTO `".$this->db_prefix."objects` (`id`, `name`, `node_id`, `creator`, `created`, `class_name`, `version`, `language`, `icon`) VALUES (1, 'Root', 1, 9, '$datetime', 'folder', 1, 'eng', ''),";
				$query .= "(2, 'Users', 2, 9, '$datetime', 'group', 1, 'eng', ''),";
				$query .= "(3, 'Public', 3, 9, '$datetime', 'folder', 1, 'eng', ''),";
				$query .= "(4, 'Anonymous', 4, 9, '$datetime', 'group', 1, 'eng', ''),";
				$query .= "(5, 'Anonymous', 5, 9, '$datetime', 'user', 1, 'eng', ''),";
				$query .= "(6, 'Read Public', 6, 9, '$datetime', 'right_read', 1, 'eng', ''),";
				$query .= "(7, 'List Public', 7, 9, '$datetime', 'right_read_subnodes', 1, 'eng', ''),";
				$query .= "(8, 'Administrators', 8, 9, '$datetime', 'group', 1, 'eng', ''),";
				$query .= "(9, 'Administrator', 9, 9, '$datetime', 'user', 1, 'eng', ''),";
				$query .= "(10, 'Create Subnodes Root', 10, 9, '$datetime', 'right_create_subnodes', 1, 'eng', ''),";
				$query .= "(11, 'Delete Root', 11, 6, '$datetime', 'right_delete', 1, 'eng', ''),";
				$query .= "(12, 'Edit Root', 12, 6, '$datetime', 'right_edit', 1, 'eng', ''),";
				$query .= "(13, 'Read Root', 13, 6, '$datetime', 'right_read', 1, 'eng', ''),";
				$query .= "(14, 'Read Subnodes Root', 14, 6, '$datetime', 'right_read_subnodes', 1, 'eng', '');";
				
				if (mysql_query($query))
					$this->db_log .= "Inserted objects into ".$this->db_prefix."objects.<br/>";
				else
				{
					$this->db_log .= "Failed to insert objects into ".$this->db_prefix."objects.<br/>";
					$this->done = false;
				}
			}

			if ($this->done)
			{
				$query = "INSERT INTO `".$this->db_prefix."values` (`id`, `data`, `object_id`, `var_id`) VALUES (1, '/Root/Public', 7, 22),";
				$query .= "(2, 'allow', 7, 16),";
				$query .= "(3, '/Root/Public', 6, 19),";
				$query .= "(4, 'allow', 6, 13),";
				$query .= "(5, '".$this->admin_username."', 9, 2),";
				$query .= "(6, '".md5($this->admin_password)."', 9, 3),";
				$query .= "(7, '/Root', 10, 18),";
				$query .= "(8, '/Root', 11, 21),";
				$query .= "(9, 'allow', 11, 15),";
				$query .= "(10, '/Root', 12, 20),";
				$query .= "(11, 'allow', 12, 14),";
				$query .= "(12, '/Root', 13, 19),";
				$query .= "(13, 'allow', 13, 13),";
				$query .= "(14, '/Root', 14, 22),";
				$query .= "(15, 'allow', 14, 16);";
				
				if (mysql_query($query))
					$this->db_log .= "Inserted values into ".$this->db_prefix."values.<br/>";
				else
				{
					$this->db_log .= "Failed to insert values into ".$this->db_prefix."values.<br/>";
					$this->done = false;
				}
			}
				
			if ($this->done)
			{
				$query = "INSERT INTO `".$this->db_prefix."vars` (`id`, `class_name`, `name`, `priority`, `type`, `extra`) VALUES (1, 'folder', 'description', 10, 'text', '100x20'),";
				$query .= "(2, 'user', 'username', 10, 'textline', ''),";
				$query .= "(3, 'user', 'password', 20, 'password', ''),";
				$query .= "(4, 'group', 'description', 10, 'text', '100x20'),";
				$query .= "(5, 'article', 'text', 10, 'xhtml', ''),";
				$query .= "(6, 'link', 'address', 10, 'textline', ''),";
				$query .= "(7, 'news', 'date', 10, 'date', ''),";
				$query .= "(8, 'news', 'text', 20, 'xhtml', ''),";
				$query .= "(38, 'event', 'year', 10, 'textline', ''),";
				$query .= "(39, 'event', 'month', 20, 'selection', '1=1,2=2,3=3,4=4,5=5,6=6,7=7,8=8,9=9,10=10,11=11,12=12,-1=Every month'),";
				$query .= "(37, 'event', 'description', 40, 'text', '100x10'),";
				$query .= "(13, 'right_read', 'setting', 10, 'selection', 'allow=Allow,deny=Deny,allowown=Allow if Creator'),";
				$query .= "(14, 'right_edit', 'setting', 10, 'selection', 'allow=Allow,deny=Deny,allowown=Allow if Creator'),";
				$query .= "(15, 'right_delete', 'setting', 10, 'selection', 'allow=Allow,deny=Deny,allowown=Allow if Creator'),";
				$query .= "(16, 'right_read_subnodes', 'setting', 10, 'selection', 'allow=Allow,deny=Deny,allowown=Allow if Creator'),";
				$query .= "(17, 'right_create_subnodes', 'classes', 10, 'array', ''),";
				$query .= "(18, 'right_create_subnodes', 'path', 5, 'textline', ''),";
				$query .= "(19, 'right_read', 'path', 5, 'textline', ''),";
				$query .= "(20, 'right_edit', 'path', 5, 'textline', ''),";
				$query .= "(21, 'right_delete', 'path', 5, 'textline', ''),";
				$query .= "(22, 'right_read_subnodes', 'path', 5, 'textline', ''),";
				$query .= "(23, 'comment', 'message', 10, 'text', '100x10'),";
				$query .= "(24, 'forum_topic', 'description', 10, 'text', '100x10'),";
				$query .= "(25, 'forum_post', 'message', 0, 'text', '100x10'),";
				$query .= "(26, 'forum_thread', 'message', 10, 'text', '100x10'),";
				$query .= "(27, 'file_folder', 'description', 10, 'text', '100x20'),";
				$query .= "(28, 'file', 'file', 10, 'file', ''),";
				$query .= "(29, 'file', 'description', 20, 'text', '100x10'),";
				$query .= "(30, 'file', 'thumbnail_id', 30, 'thumbnailid', ''),";
				$query .= "(40, 'event', 'day', 30, 'selection', '1=1,2=2,3=3,4=4,5=5,6=6,7=7,8=8,9=9,10=10,11=11,12=12,13=13,14=14,15=15,16=16,17=17,18=18,19=19,20=20,21=21,22=22,23=23,24=24,25=25,26=26,27=27,28=28,29=29,30=30,31=31');";
				
				if (mysql_query($query))
					$this->db_log .= "Inserted vars into ".$this->db_prefix."vars.<br/>";
				else
				{
					$this->db_log .= "Failed to insert vars into ".$this->db_prefix."vars.<br/>";
					$this->done = false;
				}
			}
			
			$conffile = fopen("$abspath/config.inc.php", "w");

			fwrite($conffile, "<?\n");
			fwrite($conffile, "\$anonymous_id = 5;\n");
			fwrite($conffile, "\$root_id = 1;\n");
			fwrite($conffile, "\n");
			fwrite($conffile, "\$mysql_address = \"".$this->db_address."\";\n");
			fwrite($conffile, "\$mysql_user = \"".$this->db_username."\";\n");
			fwrite($conffile, "\$mysql_pass = \"".$this->db_password."\";\n");
			fwrite($conffile, "\$mysql_db = \"".$this->db_name."\";\n");
			fwrite($conffile, "\$db_prefix = \"".$this->db_prefix."\";\n");
			fwrite($conffile, "\n");
			fwrite($conffile, "\$default_theme = \"".$this->site."\";\n");
			fwrite($conffile, "?>");

			fclose($conffile);

			chmod("$abspath/config.inc.php", 0600);

			$this->db_log .= "Wrote config, $abspath/config.inc.php.<br/>";
		}
		
		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		global $wwwpath, $abspath;
		ob_start();
		include(gettpl("install/stage".$args['stage']));
		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
	}
}
?>