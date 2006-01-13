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
		$this->db_exists = false;
		$this->db_tables = false;
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

			if (!$db_conn = @mysql_connect($this->db_address, $this->db_username, $this->db_password))
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
							global $db_prefix;
							$db_prefix = $this->db_prefix;
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
				$query .= "('file', 'file', 18),";
				$query .= "('internal_link', 'intern', 19),";
				$query .= "('contact', 'user', 20),";
				$query .= "('right', 'right', 21);";
				
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
				$query = "INSERT INTO `".$this->db_prefix."vars` (`id`, `class_name`, `name`, `priority`, `type`, `extra`) VALUES (1, 'folder', 'description', 10, 'text', '100x20'),";
				$query .= "(2, 'user', 'username', 10, 'textline', ''),";
				$query .= "(3, 'user', 'password', 20, 'password', ''),";
				$query .= "(4, 'group', 'description', 10, 'text', '100x20'),";
				$query .= "(5, 'article', 'text', 10, 'xhtml', ''),";
				$query .= "(6, 'link', 'address', 10, 'textline', ''),";
				$query .= "(7, 'news', 'date', 10, 'date', ''),";
				$query .= "(8, 'news', 'text', 20, 'xhtml', ''),";
				$query .= "(9, 'event', 'date', 10, 'textline', ''),";
				$query .= "(10, 'event', 'reoccuring_yearly', 20, 'boolean', ''),";
				$query .= "(11, 'event', 'reoccuring_monthly', 30, 'boolean', ''),";
				$query .= "(12, 'event', 'reoccuring_daily', 40, 'boolean', ''),";
				$query .= "(13, 'event', 'description', 10, 'text', '100x20'),";
				$query .= "(14, 'right_read', 'setting', 10, 'selection', 'allow=Allow,deny=Deny,allowown=Allow if Creator'),";
				$query .= "(15, 'right_read', 'path', 5, 'textline', ''),";
				$query .= "(16, 'right_edit', 'setting', 10, 'selection', 'allow=Allow,deny=Deny,allowown=Allow if Creator'),";
				$query .= "(17, 'right_edit', 'path', 5, 'textline', ''),";
				$query .= "(18, 'right_delete', 'setting', 10, 'selection', 'allow=Allow,deny=Deny,allowown=Allow if Creator'),";
				$query .= "(19, 'right_delete', 'path', 5, 'textline', ''),";
				$query .= "(20, 'right_read_subnodes', 'setting', 10, 'selection', 'allow=Allow,deny=Deny,allowown=Allow if Creator'),";
				$query .= "(21, 'right_read_subnodes', 'path', 5, 'textline', ''),";
				$query .= "(22, 'right_create_subnodes', 'classes', 10, 'array', ''),";
				$query .= "(23, 'right_create_subnodes', 'path', 5, 'textline', ''),";
				$query .= "(24, 'comment', 'message', 10, 'text', '100x10'),";
				$query .= "(25, 'forum_topic', 'description', 10, 'text', '100x10'),";
				$query .= "(26, 'forum_post', 'message', 0, 'text', '100x10'),";
				$query .= "(27, 'forum_thread', 'message', 10, 'text', '100x10'),";
				$query .= "(28, 'file_folder', 'description', 10, 'text', '100x20'),";
				$query .= "(29, 'file', 'file', 10, 'file', ''),";
				$query .= "(30, 'file', 'description', 20, 'text', '100x10'),";
				$query .= "(31, 'file', 'thumbnail_id', 30, 'thumbnailid', ''),";
				$query .= "(32, 'file', 'imagecache_id', 40, 'thumbnailid', ''),";
				$query .= "(33, 'internal_link', 'command', 10, 'textline', ''),";
				$query .= "(34, 'contact', 'thumbnail', 10, 'thumbnail', ''),";
				$query .= "(35, 'contact', 'fullname', 20, 'textline', ''),";
				$query .= "(36, 'contact', 'nicknames', 30, 'array', ''),";
				$query .= "(37, 'contact', 'emails', 50, 'array', ''),";
				$query .= "(38, 'contact', 'thumbnail', 60, 'thumbnail', ''),";
				$query .= "(39, 'contact', 'mobilephone', 70, 'textline', ''),";
				$query .= "(40, 'contact', 'homephone', 80, 'textline', ''),";
				$query .= "(41, 'contact', 'workphone', 90, 'textline', ''),";
				$query .= "(42, 'contact', 'address', 100, 'text', ''),";
				$query .= "(43, 'contact', 'icq', 110, 'textline', ''),";
				$query .= "(44, 'contact', 'msn', 120, 'textline', ''),";
				$query .= "(45, 'contact', 'skype', 130, 'textline', ''),";
				$query .= "(46, 'contact', 'allergies', 140, 'array', ''),";
				$query .= "(47, 'contact', 'other', 150, 'text', ''),";
				$query .= "(48, 'right', 'node', 10, 'node', ''),";
				$query .= "(49, 'right', 'setting', 20, 'selection', 'allow=allow,deny=deny,allowown=allow if author'),";
				$query .= "(50, 'right', 'type', 30, 'selection', 'all=all,read=read,edit=edit,delete=delete,list_sub=list children,create_sub=create children'),";
				$query .= "(51, 'right', 'inheritable', 40, 'boolean', ''),";
				$query .= "(52, 'right', 'classes', 50, 'array', ''),";
				$query .= "(53, 'right', 'description', 60, 'text', '');";
				
				if (mysql_query($query))
					$this->db_log .= "Inserted vars into ".$this->db_prefix."vars.<br/>";
				else
				{
					$this->db_log .= "Failed to insert vars into ".$this->db_prefix."vars.<br/>";
					$this->done = false;
				}
			}

			// Insert initial objects

			/*
			==========
			== Root ==
			==========
			*/
			$root_obj = new mObject();
			$root_obj->setClassName("folder");
			$root_obj->loadVars();
			$root_obj->setLanguage("eng");
			$root_obj->setName("Root");
			$root_obj->setIcon("murrix");
			
			$root_obj->setVarValue("description", "MURRiX");
			$root_obj->setVarValue("icon", "murrix");

			if ($root_obj->save())
				$this->db_log .= "Created ".$root_obj->getName().".<br/>";
			else
			{
				$this->db_log .= "Failed to create ".$root_obj->getName().".<br/>";
				$this->done = false;
			}

			/*
			===========
			== Home ==
			===========
			*/
			$home_obj = new mObject();
			$home_obj->setClassName("folder");
			$home_obj->loadVars();
			$home_obj->setLanguage("eng");
			$home_obj->setName("Home");
			$home_obj->setIcon("home");

			$home_obj->setVarValue("description", "This folder contains the groups home folders");
			
			if ($home_obj->save())
			{
				$home_obj->linkWithNode($root_obj->getNodeId());
				$this->db_log .= "Created ".$home_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$home_obj->getName().".<br/>";
				$this->done = false;
			}
			
			/*
			============
			== Public ==
			============
			*/
			$public_obj = new mObject();
			$public_obj->setClassName("folder");
			$public_obj->loadVars();
			$public_obj->setLanguage("eng");
			$public_obj->setName("Public");

			$public_obj->setVarValue("description", "This folder is readable by anyone");
			
			if ($public_obj->save())
			{
				$public_obj->linkWithNode($root_obj->getNodeId());
				$this->db_log .= "Created ".$public_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$public_obj->getName().".<br/>";
				$this->done = false;
			}
			
			/*
			==========
			== Menu ==
			==========
			*/
			$menu_obj = new mObject();
			$menu_obj->setClassName("folder");
			$menu_obj->loadVars();
			$menu_obj->setLanguage("eng");
			$menu_obj->setName("Menu");

			$menu_obj->setVarValue("description", "This folder contains the objects visible in the menu");
			
			if ($menu_obj->save())
			{
				$menu_obj->linkWithNode($public_obj->getNodeId());
				$this->db_log .= "Created ".$menu_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$menu_obj->getName().".<br/>";
				$this->done = false;
			}
			
			/*
			===========
			== Users ==
			===========
			*/
			$users_obj = new mObject();
			$users_obj->setClassName("group");
			$users_obj->loadVars();
			$users_obj->setLanguage("eng");
			$users_obj->setName("Users");

			$users_obj->setVarValue("description", "This folder contains all users, groups and rights");
			
			if ($users_obj->save())
			{
				$users_obj->linkWithNode($root_obj->getNodeId());
				$this->db_log .= "Created ".$users_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$users_obj->getName().".<br/>";
				$this->done = false;
			}

			/*
			======================
			== Group: Anonymous ==
			======================
			*/
			$group_anon_obj = new mObject();
			$group_anon_obj->setClassName("group");
			$group_anon_obj->loadVars();
			$group_anon_obj->setLanguage("eng");
			$group_anon_obj->setName("Anonymous");

			$group_anon_obj->setVarValue("description", "This group contains the anonymous user and it's rights");
			
			if ($group_anon_obj->save())
			{
				$group_anon_obj->linkWithNode($users_obj->getNodeId());
				$this->db_log .= "Created ".$group_anon_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$group_anon_obj->getName().".<br/>";
				$this->done = false;
			}

			/*
			======================
			== User: Anonymous ==
			======================
			*/
			$user_anon_obj = new mObject();
			$user_anon_obj->setClassName("user");
			$user_anon_obj->loadVars();
			$user_anon_obj->setLanguage("eng");
			$user_anon_obj->setName("Anonymous");

			$user_anon_obj->setVarValue("description", "This is the anonymous user");
			
			if ($user_anon_obj->save())
			{
				$user_anon_obj->linkWithNode($group_anon_obj->getNodeId());
				$this->db_log .= "Created ".$user_anon_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$user_anon_obj->getName().".<br/>";
				$this->done = false;
			}
			
			/*
			===================================
			== Right Read: Read Right Public ==
			===================================

			$read_public_obj = new mObject();
			$read_public_obj->setClassName("right_read");
			$read_public_obj->loadVars();
			$read_public_obj->setLanguage("eng");
			$read_public_obj->setName("Read Right Public");

			$read_public_obj->setVarValue("setting", "allow");
			$read_public_obj->setVarValue("path", "/Root/Public");
			$read_public_obj->setVarValue("description", "This right gives read access to /Root/Public");
			
			if ($read_public_obj->save())
			{
				$read_public_obj->linkWithNode($group_anon_obj->getNodeId());
				$this->db_log .= "Created ".$read_public_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$read_public_obj->getName().".<br/>";
				$this->done = false;
			}*/
			
			/*
			============================================
			== Right Read Subnodes: List Right Public ==
			============================================

			$list_public_obj = new mObject();
			$list_public_obj->setClassName("right_read_subnodes");
			$list_public_obj->loadVars();
			$list_public_obj->setLanguage("eng");
			$list_public_obj->setName("List Right Public");

			$list_public_obj->setVarValue("setting", "allow");
			$list_public_obj->setVarValue("path", "/Root/Public");
			$list_public_obj->setVarValue("description", "This right gives list access to /Root/Public");
			
			if ($list_public_obj->save())
			{
				$list_public_obj->linkWithNode($group_anon_obj->getNodeId());
				$this->db_log .= "Created ".$list_public_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$list_public_obj->getName().".<br/>";
				$this->done = false;
			}*/
			
			/*
			===========================
			== Group: Administrators ==
			===========================
			*/
			$group_admin_obj = new mObject();
			$group_admin_obj->setClassName("group");
			$group_admin_obj->loadVars();
			$group_admin_obj->setLanguage("eng");
			$group_admin_obj->setName("Administrators");

			$group_admin_obj->setVarValue("description", "This group contains the administrators and their rights");
			
			if ($group_admin_obj->save())
			{
				$group_admin_obj->linkWithNode($users_obj->getNodeId());
				$this->db_log .= "Created ".$group_admin_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$group_admin_obj->getName().".<br/>";
				$this->done = false;
			}

			/*
			=========================
			== User: Administrator ==
			=========================
			*/
			$user_admin_obj = new mObject();
			$user_admin_obj->setClassName("user");
			$user_admin_obj->loadVars();
			$user_admin_obj->setLanguage("eng");
			$user_admin_obj->setName("Administrator");

			$user_admin_obj->setVarValue("username", $this->admin_username);
			$user_admin_obj->setVarValue("password", md5($this->admin_password));
			$user_admin_obj->setVarValue("description", "This is the administrator");
			
			if ($user_admin_obj->save())
			{
				$user_admin_obj->linkWithNode($group_admin_obj->getNodeId());
				$this->db_log .= "Created ".$user_admin_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$user_admin_obj->getName().".<br/>";
				$this->done = false;
			}

			/*
			===================================
			== Right Read: Read Right Root ==
			===================================

			$read_root_obj = new mObject();
			$read_root_obj->setClassName("right_read");
			$read_root_obj->loadVars();
			$read_root_obj->setLanguage("eng");
			$read_root_obj->setName("Read Right Root");

			$read_root_obj->setVarValue("setting", "allow");
			$read_root_obj->setVarValue("path", "/Root");
			$read_root_obj->setVarValue("description", "This right gives read access to /Root");
			
			if ($read_root_obj->save())
			{
				$read_root_obj->linkWithNode($group_admin_obj->getNodeId());
				$this->db_log .= "Created ".$read_root_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$read_root_obj->getName().".<br/>";
				$this->done = false;
			}*/

			/*
			===================================
			== Right List: List Right Root ==
			===================================

			$list_root_obj = new mObject();
			$list_root_obj->setClassName("right_read_subnodes");
			$list_root_obj->loadVars();
			$list_root_obj->setLanguage("eng");
			$list_root_obj->setName("List Right Root");

			$list_root_obj->setVarValue("setting", "allow");
			$list_root_obj->setVarValue("path", "/Root");
			$list_root_obj->setVarValue("description", "This right gives list access to /Root");
			
			if ($list_root_obj->save())
			{
				$list_root_obj->linkWithNode($group_admin_obj->getNodeId());
				$this->db_log .= "Created ".$list_root_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$list_root_obj->getName().".<br/>";
				$this->done = false;
			}*/

			/*
			===================================
			== Right Create: Create Right Root ==
			===================================

			$create_root_obj = new mObject();
			$create_root_obj->setClassName("right_create_subnodes");
			$create_root_obj->loadVars();
			$create_root_obj->setLanguage("eng");
			$create_root_obj->setName("Create Right Root");

			$create_root_obj->setVarValue("path", "/Root");
			$create_root_obj->setVarValue("description", "This right gives right to creation of objects under /Root");
			
			if ($create_root_obj->save())
			{
				$create_root_obj->linkWithNode($group_admin_obj->getNodeId());
				$this->db_log .= "Created ".$create_root_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$create_root_obj->getName().".<br/>";
				$this->done = false;
			}*/
			
			/*
			===================================
			== Right Delete: Delete Right Root ==
			===================================

			$delete_root_obj = new mObject();
			$delete_root_obj->setClassName("right_delete");
			$delete_root_obj->loadVars();
			$delete_root_obj->setLanguage("eng");
			$delete_root_obj->setName("Delete Right Root");

			$delete_root_obj->setVarValue("setting", "allow");
			$delete_root_obj->setVarValue("path", "/Root");
			$delete_root_obj->setVarValue("description", "This right gives right to delete under /Root");
			
			if ($delete_root_obj->save())
			{
				$delete_root_obj->linkWithNode($group_admin_obj->getNodeId());
				$this->db_log .= "Created ".$delete_root_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$delete_root_obj->getName().".<br/>";
				$this->done = false;
			}*/
			
			/*
			===================================
			== Edit Delete: Edit Right Root ==
			===================================

			$edit_root_obj = new mObject();
			$edit_root_obj->setClassName("right_edit");
			$edit_root_obj->loadVars();
			$edit_root_obj->setLanguage("eng");
			$edit_root_obj->setName("Edit Right Root");

			$edit_root_obj->setVarValue("setting", "allow");
			$edit_root_obj->setVarValue("path", "/Root");
			$edit_root_obj->setVarValue("description", "This right gives right to edit /Root");
			
			if ($edit_root_obj->save())
			{
				$edit_root_obj->linkWithNode($group_admin_obj->getNodeId());
				$this->db_log .= "Created ".$edit_root_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$edit_root_obj->getName().".<br/>";
				$this->done = false;
			}*/
			
			/* ======================================================================================= */
			// Anonymous rights

			$right1 = new mObject();
			$right1->setClassName("right");
			$right1->loadVars();
			$right1->setLanguage("eng");
			$right1->setName("Read right on Root");

			$right1->setVarValue("node", $root_obj->getNodeId());
			$right1->setVarValue("setting", "allow");
			$right1->setVarValue("type", "read");
			$right1->setVarValue("inheritable", false);
			$right1->setVarValue("description", "This right gives read access to /Root");
			
			if ($right1->save())
			{
				$right1->linkWithNode($group_anon_obj->getNodeId());
				$this->db_log .= "Created ".$right1->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$right1->getName().".<br/>";
				$this->done = false;
			}

			$right2 = new mObject();
			$right2->setClassName("right");
			$right2->loadVars();
			$right2->setLanguage("eng");
			$right2->setName("Read right on Public");

			$right2->setVarValue("node", $public_obj->getNodeId());
			$right2->setVarValue("setting", "allow");
			$right2->setVarValue("type", "read");
			$right2->setVarValue("inheritable", true);
			$right2->setVarValue("description", "This right gives read access to /Root/Public/*");
			
			if ($right2->save())
			{
				$right2->linkWithNode($group_anon_obj->getNodeId());
				$this->db_log .= "Created ".$right2->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$right2->getName().".<br/>";
				$this->done = false;
			}

			$right3 = new mObject();
			$right3->setClassName("right");
			$right3->loadVars();
			$right3->setLanguage("eng");
			$right3->setName("List right on Public");

			$right3->setVarValue("node", $public_obj->getNodeId());
			$right3->setVarValue("setting", "allow");
			$right3->setVarValue("type", "list_sub");
			$right3->setVarValue("inheritable", true);
			$right3->setVarValue("description", "This right gives list access to /Root/Public/*");
			
			if ($right3->save())
			{
				$right3->linkWithNode($group_anon_obj->getNodeId());
				$this->db_log .= "Created ".$right3->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$right3->getName().".<br/>";
				$this->done = false;
			}

			$right4 = new mObject();
			$right4->setClassName("right");
			$right4->loadVars();
			$right4->setLanguage("eng");
			$right4->setName("Read right on Home");

			$right4->setVarValue("node", $home_obj->getNodeId());
			$right4->setVarValue("setting", "allow");
			$right4->setVarValue("type", "read");
			$right4->setVarValue("inheritable", false);
			$right4->setVarValue("description", "This right gives read access to /Root/Home");
			
			if ($right4->save())
			{
				$right4->linkWithNode($group_anon_obj->getNodeId());
				$this->db_log .= "Created ".$right4->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$right4->getName().".<br/>";
				$this->done = false;
			}

			$right5 = new mObject();
			$right5->setClassName("right");
			$right5->loadVars();
			$right5->setLanguage("eng");
			$right5->setName("All access on Root");

			$right5->setVarValue("node", $root_obj->getNodeId());
			$right5->setVarValue("setting", "allow");
			$right5->setVarValue("type", "all");
			$right5->setVarValue("inheritable", true);
			$right5->setVarValue("description", "This right gives all access to /Root/*");
			
			if ($right5->save())
			{
				$right5->linkWithNode($group_admin_obj->getNodeId());
				$this->db_log .= "Created ".$right5->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$right5->getName().".<br/>";
				$this->done = false;
			}

			/* ======================================================================================= */

			$confdata = "<?\n";
			$confdata .= "\$anonymous_id = ".$user_anon_obj->getNodeId().";\n";
			$confdata .= "\$root_id = ".$root_obj->getNodeId().";\n";
			$confdata .= "\n";
			$confdata .= "\$mysql_address = \"".$this->db_address."\";\n";
			$confdata .= "\$mysql_user = \"".$this->db_username."\";\n";
			$confdata .= "\$mysql_pass = \"".$this->db_password."\";\n";
			$confdata .= "\$mysql_db = \"".$this->db_name."\";\n";
			$confdata .= "\$db_prefix = \"".$this->db_prefix."\";\n";
			$confdata .= "\n";
			$confdata .= "\$default_theme = \"".$this->site."\";\n";
			$confdata .= "?>";

			if (is_writable($abspath))
			{
				$conffile = fopen("$abspath/config.inc.php", "w");
				fwrite($conffile, $confdata);
				fclose($conffile);
				chmod("$abspath/config.inc.php", 0600);
				$this->db_log .= "Wrote config, $abspath/config.inc.php.<br/>";
			}
			else
			{
				$this->db_log .= "Unable to write config file.<br/>";
				$this->db_log .= "Please put the folowing into \"config.inc.php\" and place it in MURRiXs rootpath:<br/>";
				$this->db_log .= "<br/>";
				$this->db_log .= nl2br($confdata);
				$this->db_log .= "<br/>";
			}
			$this->db_log .= "Installation complete!<br/>";
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