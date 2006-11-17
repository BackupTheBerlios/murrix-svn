<?

class sInstall extends Script
{
	function sInstall()
	{
		$this->db = array();
		$this->db['adress'] = "localhost";
		$this->db['name'] = "murrix";
		$this->db['prefix'] = "murrix_";
		$this->db['address'] = "localhost";
		$this->db['username'] = "";
		$this->db['password'] = "";
		
		$this->config = array();
		$this->config['username'] = "admin";
		$this->config['password'] = "";
		$this->config['theme'] = "murrix";
		$this->config['imgsize'] = 550;
		$this->config['thumbsize'] = 150;
		$this->config['instantthumbs'] = "true";
		$this->config['default_path'] = "/root/public";
		$this->config['default_lang'] = "eng";
		$this->config['transport'] = "standard";
	
		$this->zone = "zone_main";
		
		$this->addActionHandler("preinstall");
		$this->addActionHandler("license");
		$this->addActionHandler("database");
		$this->addActionHandler("databasecheck");
		$this->addActionHandler("config");
		$this->addActionHandler("finish");
	}
	
	function actionPreinstall(&$system, $args)
	{
		$checks = array();
		
		// Check PHP version
		// Check MySQL support and version
		
		global $abspath;
		
		$checks['root']['text'] = "Checking if '$abspath' is writable";
		$checks['root']['status'] = is_writable("$abspath");
		$checks['root']['fatal'] = true;
		
		$checks['cache']['text'] = "Checking if '$abspath/cache' is writable";
		$checks['cache']['status'] = is_writable("$abspath/cache");
		$checks['cache']['fatal'] = true;
		
		$checks['files']['text'] = "Checking if '$abspath/files' is writable";
		$checks['files']['status'] = is_writable("$abspath/files");
		$checks['files']['fatal'] = true;
		
		$checks['thumbnails']['text'] = "Checking if '$abspath/thumbnails' is writable";
		$checks['thumbnails']['status'] = is_writable("$abspath/thumbnails");
		$checks['thumbnails']['fatal'] = true;
	
		$data = compiletpl("preinstall", array("checks"=>$checks));
		$system->setZoneData($this->zone, utf8e($data));
		
		$data = compiletpl("menu", array("action"=>"preinstall"));
		$system->setZoneData("zone_menu", utf8e($data));
	}
	
	function actionLicense(&$system, $args)
	{
		$data = compiletpl("license", array());
		$system->setZoneData($this->zone, utf8e($data));
		
		$data = compiletpl("menu", array("action"=>"license"));
		$system->setZoneData("zone_menu", utf8e($data));
	}
	
	function actionDatabase(&$system, $args)
	{
		$data = compiletpl("database", $this->db);
		$system->setZoneData($this->zone, utf8e($data));
		
		$data = compiletpl("menu", array("action"=>"database"));
		$system->setZoneData("zone_menu", utf8e($data));
	}
	
	function actionDatabasecheck(&$system, $args)
	{
		global $abspath;
		
		if (empty($args['adress']))
		{
			$system->addAlert("You must enter a server adress");
			return;
		}
		
		if (empty($args['name']))
		{
			$system->addAlert("You must enter a database name");
			return;
		}
		
		if (empty($args['username']))
		{
			$system->addAlert("You must enter a username");
			return;
		}
		
		if (empty($args['password']))
		{
			$system->addAlert("You must enter a password");
			return;
		}
		
		unset($args['action']);
		$this->db = $args;
		
		$checks = array();

		$checks['connect']['text'] = "Trying to connect to database";
		$checks['connect']['fatal'] = true;

		if (!$db_conn = @mysql_connect($this->db['adress'], $this->db['username'], $this->db['password']))
		{
			$checks['connect']['status'] = false;
			//$logtext .= "Error connecting to MySQL: " . mysql_errno() . " " . mysql_error()."<br/>";
		}
		else
		{
			$checks['connect']['status'] = true;
			
			$checks['database']['text'] = "Checking for existing database";
			$checks['database']['fatal'] = false;
			
			
				
			if (mysql_select_db($this->db['name']))
			{
				$checks['database']['status'] = true;
			
				$checks['tables']['text'] = "Checking for existing tables";
				$checks['tables']['fatal'] = false;
				$checks['tables']['status'] = false;
				
				$files = GetSubfiles("$abspath/scripts/install/db");
				foreach ($files as $table)
				{
					$table = basename($table, ".sql");
					if (mysql_query("SELECT * FROM `".$this->db['prefix']."$table`"))
					{
						$checks['tables']['status'] = true;
						break;
					}
				}
			}
			else
				$checks['database']['status'] = false;
		}
		
		
		$data = compiletpl("databasecheck", array("checks"=>$checks));
		$system->setZoneData($this->zone, utf8e($data));
		
		$data = compiletpl("menu", array("action"=>"databasecheck"));
		$system->setZoneData("zone_menu", utf8e($data));
	}
	
	function actionConfig(&$system, $args)
	{
		$data = compiletpl("config", $this->config);
		$system->setZoneData($this->zone, utf8e($data));
		
		$data = compiletpl("menu", array("action"=>"config"));
		$system->setZoneData("zone_menu", utf8e($data));
	}
	
	function actionFinish(&$system, $args)
	{
		global $abspath, $db_prefix;
		$db_prefix = $this->db['prefix'];
		
		if (empty($args['theme']))
		{
			$system->addAlert("You must enter select a theme");
			return;
		}
		
		if (empty($args['imgsize']))
		{
			$system->addAlert("You must enter a imagesize");
			return;
		}
		
		if (empty($args['thumbsize']))
		{
			$system->addAlert("You must enter a thumbnailsize");
			return;
		}
		
		if (empty($args['default_lang']))
		{
			$system->addAlert("You must enter a default language");
			return;
		}
		
		if (empty($args['default_path']))
		{
			$system->addAlert("You must enter a default path");
			return;
		}
		
		if (empty($args['password']))
		{
			$system->addAlert("You must enter a password");
			return;
		}
		
		unset($args['action']);
		$this->config = $args;
		
		
		$logtext = "";
			
		if (!$db_conn = mysql_pconnect($this->db['adress'], $this->db['username'], $this->db['password']))
		{
			$system->addAlert("Error connecting to MySQL: " . mysql_errno() . " " . mysql_error());
			return;
		}
			
		if (!mysql_select_db($this->db['name']))
		{
			if (mysql_query("CREATE DATABASE `".$this->db['name']."`"))
				$logtext .= "Database ".$this->db['name']." created.<br/>";
			else
			{
				$system->addAlert("Failed to create database ".$this->db['name'].". Error: " . mysql_errno() . " " . mysql_error());
				return;
			}
		}

		mysql_select_db($this->db['name']);
		
		$files = GetSubfiles("$abspath/scripts/install/db");
		foreach ($files as $file)
		{
			$query = "DROP TABLE IF EXISTS `".$this->db['prefix'].basename($file, ".sql")."`";
			mysql_query($query);
		
			$query = str_replace("%PREFIX%", $this->db['prefix'], implode("", file("$abspath/scripts/install/db/$file")));
			
			if (mysql_query($query))
				$logtext .= "Imported $file successfully.<br/>";
			else
			{
				$system->addAlert("Failed to import $file. Error: " . mysql_errno() . " " . mysql_error()."\n$query");
				return;
			}
		}
		
		$xml = new mXml();
		
		$files = GetSubfiles("$abspath/scripts/install/classes");
		foreach ($files as $file)
		{
			$filedata = getFileData($file, "$abspath/scripts/install/classes/$file");
			$msgid = $xml->parseXML(array("node_id" => 0, "data" => $filedata));
			
			$logtext .= mMsg::getText($msgid);
			
			if (mMsg::isError($msgid))
			{
				$system->addAlert(mMsg::getError($msgid));
				return;
			}
			else
				$logtext .= "Imported $file successfully.<br/>";
		}

		$files = GetSubfiles("$abspath/scripts/install/objects");
		foreach ($files as $file)
		{
			$filedata = getFileData($file, "$abspath/scripts/install/objects/$file");
			$msgid = $xml->parseXML(array("node_id" => 0, "data" => $filedata));
			
			$logtext .= mMsg::getText($msgid);
			
			if (mMsg::isError($msgid))
			{
				$system->addAlert(mMsg::getError($msgid));
				return;
			}
			else
				$logtext .= "Imported $file successfully.<br/>";
		}
		
		$admin_group_id = createGroup("admins", "Administrators group", true);
		
		$anon_user_id = createUser("Anonymous", "", "", "", "", false);
		$admin_user_id = createUser("Administrator", "admin", $this->config['password'], "", "admins", true);
		
		
		$files = GetSubfiles("$abspath/cache");
		foreach ($files as $file)
			unlink("$abspath/cache/$file");
			
		$files = GetSubfiles("$abspath/files");
		foreach ($files as $file)
			unlink("$abspath/files/$file");
			
		$files = GetSubfiles("$abspath/thumbnails");
		foreach ($files as $file)
			unlink("$abspath/thumbnails/$file");
		
		// Insert initial objects
/*
		$root_obj = new mObject();
		$root_obj->setClassName("folder");
		$root_obj->loadVars();
		$root_obj->setLanguage("eng");
		$root_obj->setName("root");
		$root_obj->setIcon("murrix");
		$root_obj->setRights("all=r");
		
		$root_obj->setVarValue("description", "This is the root node");

		if ($root_obj->save())
		{
			$root_obj->setMeta("initial_rights", "admins=rwc");
			$logtext .= "Created ".$root_obj->getName().".<br/>";
		}
		else
		{
			$logtext .= "Failed to create ".$root_obj->getName().".<br/>";
			$logtext .= $root_obj->error;
			$this->done = false;
		}

		$home_obj = new mObject();
		$home_obj->setClassName("folder");
		$home_obj->loadVars();
		$home_obj->setLanguage("eng");
		$home_obj->setName("home");
		$home_obj->setIcon("home");
		$home_obj->setRights("all=r");

		$home_obj->setVarValue("description", "This folder contain home folders");
		
		if ($home_obj->save())
		{
			$home_obj->linkWithNode($root_obj->getNodeId());
			$logtext .= "Created ".$home_obj->getName().".<br/>";
		}
		else
		{
			$logtext .= "Failed to create ".$home_obj->getName().".<br/>";
			$logtext .= $home_obj->error;
			$this->done = false;
		}
		
		$users_home_obj = new mObject();
		$users_home_obj->setClassName("folder");
		$users_home_obj->loadVars();
		$users_home_obj->setLanguage("eng");
		$users_home_obj->setName("users");
		$users_home_obj->setIcon("user");
		$users_home_obj->setRights("all=r");

		$users_home_obj->setVarValue("description", "This folder contain home folders");
		
		if ($users_home_obj->save())
		{
			$users_home_obj->linkWithNode($home_obj->getNodeId());
			$logtext .= "Created ".$users_home_obj->getName().".<br/>";
		}
		else
		{
			$logtext .= "Failed to create ".$users_home_obj->getName().".<br/>";
			$logtext .= $users_home_obj->error;
			$this->done = false;
		}
		
		$group_home_obj = new mObject();
		$group_home_obj->setClassName("folder");
		$group_home_obj->loadVars();
		$group_home_obj->setLanguage("eng");
		$group_home_obj->setName("groups");
		$group_home_obj->setIcon("group2");
		$group_home_obj->setRights("all=r");

		$users_home_obj->setVarValue("description", "This folder contain group folders");
		
		if ($group_home_obj->save())
		{
			$group_home_obj->linkWithNode($home_obj->getNodeId());
			$logtext .= "Created ".$group_home_obj->getName().".<br/>";
		}
		else
		{
			$logtext .= "Failed to create ".$group_home_obj->getName().".<br/>";
			$logtext .= $group_home_obj->error;
			$this->done = false;
		}
		
		$adminhome_obj = new mObject();
		$adminhome_obj->setClassName("folder");
		$adminhome_obj->loadVars();
		$adminhome_obj->setLanguage("eng");
		$adminhome_obj->setName($this->admin_username);
		$adminhome_obj->setRights("admins=rwc");

		$adminhome_obj->setVarValue("description", "This is the home for ".$this->admin_username);
		
		if ($adminhome_obj->save())
		{
			$adminhome_obj->linkWithNode($users_home_obj->getNodeId());
			$logtext .= "Created ".$adminhome_obj->getName().".<br/>";
			
			$administrator->home_id = $adminhome_obj->getNodeId();
			$administrator->save();
		}
		else
		{
			$logtext .= "Failed to create ".$adminhome_obj->getName().".<br/>";
			$logtext .= $adminhome_obj->error;
			$this->done = false;
		}
		
		$adminshome_obj = new mObject();
		$adminshome_obj->setClassName("folder");
		$adminshome_obj->loadVars();
		$adminshome_obj->setLanguage("eng");
		$adminshome_obj->setName("admins");
		$adminshome_obj->setRights("admins=rwc");

		$adminshome_obj->setVarValue("description", "This is the home for admins");
		
		if ($adminshome_obj->save())
		{
			$adminshome_obj->linkWithNode($group_home_obj->getNodeId());
			$logtext .= "Created ".$adminshome_obj->getName().".<br/>";
			
			$administrator_group->home_id = $adminshome_obj->getNodeId();
			$administrator_group->save();
		}
		else
		{
			$logtext .= "Failed to create ".$adminshome_obj->getName().".<br/>";
			$logtext .= $adminshome_obj->error;
			$this->done = false;
		}
		
		$public_obj = new mObject();
		$public_obj->setClassName("folder");
		$public_obj->loadVars();
		$public_obj->setLanguage("eng");
		$public_obj->setName("public");
		$public_obj->setRights("all=r");

		$public_obj->setVarValue("description", "This folder is readable by anyone");
		
		if ($public_obj->save())
		{
			$public_obj->linkWithNode($root_obj->getNodeId());
			$logtext .= "Created ".$public_obj->getName().".<br/>";
		}
		else
		{
			$logtext .= "Failed to create ".$public_obj->getName().".<br/>";
			$logtext .= $public_obj->error;
			$this->done = false;
		}
		*/
		
		
		
		setSetting("ROOT_NODE_ID",	1,				"any");
		setSetting("ANONYMOUS_ID",	$anonymous->id,			"any");
		setSetting("TRANSPORT",		$this->config['transport'],	"any");
		setSetting("DEFAULT_THEME",	$this->config['theme'],		"any");
		
		setSetting("IMGSIZE",		$this->config['imgsize'],	$this->config['theme']);
		setSetting("THUMBSIZE",		$this->config['thumbsize'],	$this->config['theme']);
		setSetting("INSTANTTHUMBS",	$this->config['instantthumbs'],	$this->config['theme']);
		setSetting("DEFAULT_PATH",	$this->config['default_path'],	$this->config['theme']);
		setSetting("DEFAULT_LANG",	$this->config['default_lang'],	$this->config['theme']);

		$confdata = "<?\n";
		$confdata .= "\$mysql_address = \"".$this->db['adress']."\";\n";
		$confdata .= "\$mysql_user = \"".$this->db['username']."\";\n";
		$confdata .= "\$mysql_pass = \"".$this->db['password']."\";\n";
		$confdata .= "\$mysql_db = \"".$this->db['name']."\";\n";
		$confdata .= "\$db_prefix = \"".$this->db['prefix']."\";\n";
		$confdata .= "?>";

		if (is_writable($abspath))
		{
			$conffile = fopen("$abspath/config.inc.php", "w");
			fwrite($conffile, $confdata);
			fclose($conffile);
			chmod("$abspath/config.inc.php", 0600);
			$logtext .= "Wrote config, $abspath/config.inc.php.<br/>";
		}
		else
		{
			$logtext .= "Unable to write config file.<br/>";
			$logtext .= "Please put the folowing into \"config.inc.php\" and place it in MURRiXs rootpath:<br/>";
			$logtext .= "<br/>";
			$logtext .= nl2br(htmlentities($confdata));
			$logtext .= "<br/>";
		}
		
		$logtext .= "Installation complete!<br/>";
		
		
		$data = compiletpl("finish", array("logtext"=>$logtext));
		$system->setZoneData($this->zone, utf8e($data));
		
		$data = compiletpl("menu", array("action"=>"finish"));
		$system->setZoneData("zone_menu", utf8e($data));
	}
	
	/*function execute(&$system, $args)
	{
		global $abspath, $wwwpath, $db_prefix;

		$db_prefix = $this->db_prefix;
	
		if (!isset($args['stage']))
			$args['stage'] = 1;

		if (isset($args['admin_username']))
			$this->admin_username = $args['admin_username'];

		if (isset($args['admin_password1']) || isset($args['admin_password2']))
		{
			if ($args['admin_password1'] != $args['admin_password2'])
			{
				$system->addAlert("Passwords don't match, please try again.");
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
				$system->addAlert("Please fill in all fields.");
				return;
			}
		}
		else if ($args['stage'] == 5)
		{
			if (empty($this->db_address) || empty($this->db_name) || empty($this->db_username) || empty($this->db_password))
			{
				$system->addAlert("Please fill in all fields. Table prefix is optional.");
				return;
			}

			$logtext = "";

			if (!$db_conn = @mysql_connect($this->db_address, $this->db_username, $this->db_password))
			{
				$logtextin = false;
				$logtext .= "Error connecting to MySQL: " . mysql_errno() . " " . mysql_error()."<br/>";
			}
			else
			{
				$logtextin = true;
				$this->db_tables = false;
				
				if (mysql_select_db($this->db_name))
				{
					$this->db_exists = true;
					$logtext .= "Database ".$this->db_name." exists.<br/>";

					$files = GetSubfiles("$abspath/scripts/install/db");
					foreach ($files as $table)
					{
						$table = basename($table, ".sql");
						if (mysql_query("SELECT * FROM `".$this->db_prefix."$table`"))
						{
							$logtext .= "Table ".$this->db_prefix."$table exists.<br/>";
							$this->db_tables = true;
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
			$logtext = "";
			
			if (!$db_conn = mysql_pconnect($this->db_address, $this->db_username, $this->db_password))
			{
				$this->done = false;
				$logtext .= "Error connecting to MySQL: " . mysql_errno() . " " . mysql_error()."<br/>";
			}

			if ($this->done)
			{
				if (!$this->db_exists)
				{
					if (mysql_query("CREATE DATABASE `".$this->db_name."`"))
						$logtext .= "Database ".$this->db_name." created.<br/>";
					else
					{
						$logtext .= "Failed to create database ".$this->db_name.". Error: " . mysql_errno() . " " . mysql_error()."<br/>";
						$this->done = false;
					}
				}

				mysql_select_db($this->db_name);
			}
			
			$files = GetSubfiles("$abspath/scripts/install/db");
			foreach ($files as $file)
			{
				$query = "DROP TABLE IF EXISTS `".$this->db_prefix.basename($file, ".sql")."`";
				mysql_query($query);
			
				$query = str_replace("%PREFIX%", $this->db_prefix, implode("", file("$abspath/scripts/install/db/$file")));
				
				if (mysql_query($query))
					$logtext .= "Imported $file successfully.<br/>";
				else
				{
					$logtext .= "Falied to import $file. Error: " . mysql_errno() . " " . mysql_error()."<br/>";
					$logtext .= $query;
					$this->done = false;
					break;
				}
			}
			
			$xml = new mXml();
			
			$files = GetSubfiles("$abspath/scripts/install/classes");
			foreach ($files as $file)
			{
				$filedata = getFileData($file, "$abspath/scripts/install/classes/$file");
				$msgid = $xml->parseXML(array("node_id" => 0, "data" => $filedata));
				
				$logtext .= mMsg::getText($msgid);
				
				if (mMsg::isError($msgid))
					$this->done = false;
				else
					$logtext .= "Imported $file successfully.<br/>";
			}

			// Create initial groups
			$administrator_group = new mGroup();
			$administrator_group->name = "admins";
			$administrator_group->description = "Administrators group";
			$administrator_group->save();
			
			// Create initial users
			
			$anonymous = new mUser();
			$anonymous->name = "Anonymous";
			$anonymous->username = "";
			$anonymous->password = "";
			$anonymous->home = "";
			$anonymous->groups = "";
			$anonymous->save();
			
			$administrator = new mUser();
			$administrator->name = "Administrator";
			$administrator->username = $this->admin_username;
			$administrator->password = md5($this->admin_password);
			$administrator->home_id = 0;
			$administrator->groups = "admins";
			$administrator->save();
			
			$_SESSION['murrix']['user'] = $administrator;
			
			// Insert initial objects

			$root_obj = new mObject();
			$root_obj->setClassName("folder");
			$root_obj->loadVars();
			$root_obj->setLanguage("eng");
			$root_obj->setName("root");
			$root_obj->setIcon("murrix");
			$root_obj->setRights("all=r");
			
			$root_obj->setVarValue("description", "This is the root node");

			if ($root_obj->save())
			{
				$root_obj->setMeta("initial_rights", "admins=rwc");
				$logtext .= "Created ".$root_obj->getName().".<br/>";
			}
			else
			{
				$logtext .= "Failed to create ".$root_obj->getName().".<br/>";
				$logtext .= $root_obj->error;
				$this->done = false;
			}

			$home_obj = new mObject();
			$home_obj->setClassName("folder");
			$home_obj->loadVars();
			$home_obj->setLanguage("eng");
			$home_obj->setName("home");
			$home_obj->setIcon("home");
			$home_obj->setRights("all=r");

			$home_obj->setVarValue("description", "This folder contain home folders");
			
			if ($home_obj->save())
			{
				$home_obj->linkWithNode($root_obj->getNodeId());
				$logtext .= "Created ".$home_obj->getName().".<br/>";
			}
			else
			{
				$logtext .= "Failed to create ".$home_obj->getName().".<br/>";
				$logtext .= $home_obj->error;
				$this->done = false;
			}
			
			$users_home_obj = new mObject();
			$users_home_obj->setClassName("folder");
			$users_home_obj->loadVars();
			$users_home_obj->setLanguage("eng");
			$users_home_obj->setName("users");
			$users_home_obj->setIcon("user");
			$users_home_obj->setRights("all=r");

			$users_home_obj->setVarValue("description", "This folder contain home folders");
			
			if ($users_home_obj->save())
			{
				$users_home_obj->linkWithNode($home_obj->getNodeId());
				$logtext .= "Created ".$users_home_obj->getName().".<br/>";
			}
			else
			{
				$logtext .= "Failed to create ".$users_home_obj->getName().".<br/>";
				$logtext .= $users_home_obj->error;
				$this->done = false;
			}
			
			$group_home_obj = new mObject();
			$group_home_obj->setClassName("folder");
			$group_home_obj->loadVars();
			$group_home_obj->setLanguage("eng");
			$group_home_obj->setName("groups");
			$group_home_obj->setIcon("group2");
			$group_home_obj->setRights("all=r");

			$users_home_obj->setVarValue("description", "This folder contain group folders");
			
			if ($group_home_obj->save())
			{
				$group_home_obj->linkWithNode($home_obj->getNodeId());
				$logtext .= "Created ".$group_home_obj->getName().".<br/>";
			}
			else
			{
				$logtext .= "Failed to create ".$group_home_obj->getName().".<br/>";
				$logtext .= $group_home_obj->error;
				$this->done = false;
			}
			
			$adminhome_obj = new mObject();
			$adminhome_obj->setClassName("folder");
			$adminhome_obj->loadVars();
			$adminhome_obj->setLanguage("eng");
			$adminhome_obj->setName($this->admin_username);
			$adminhome_obj->setRights("admins=rwc");

			$adminhome_obj->setVarValue("description", "This is the home for ".$this->admin_username);
			
			if ($adminhome_obj->save())
			{
				$adminhome_obj->linkWithNode($users_home_obj->getNodeId());
				$logtext .= "Created ".$adminhome_obj->getName().".<br/>";
				
				$administrator->home_id = $adminhome_obj->getNodeId();
				$administrator->save();
			}
			else
			{
				$logtext .= "Failed to create ".$adminhome_obj->getName().".<br/>";
				$logtext .= $adminhome_obj->error;
				$this->done = false;
			}
			
			$adminshome_obj = new mObject();
			$adminshome_obj->setClassName("folder");
			$adminshome_obj->loadVars();
			$adminshome_obj->setLanguage("eng");
			$adminshome_obj->setName("admins");
			$adminshome_obj->setRights("admins=rwc");

			$adminshome_obj->setVarValue("description", "This is the home for admins");
			
			if ($adminshome_obj->save())
			{
				$adminshome_obj->linkWithNode($group_home_obj->getNodeId());
				$logtext .= "Created ".$adminshome_obj->getName().".<br/>";
				
				$administrator_group->home_id = $adminshome_obj->getNodeId();
				$administrator_group->save();
			}
			else
			{
				$logtext .= "Failed to create ".$adminshome_obj->getName().".<br/>";
				$logtext .= $adminshome_obj->error;
				$this->done = false;
			}
			
			$public_obj = new mObject();
			$public_obj->setClassName("folder");
			$public_obj->loadVars();
			$public_obj->setLanguage("eng");
			$public_obj->setName("public");
			$public_obj->setRights("all=r");

			$public_obj->setVarValue("description", "This folder is readable by anyone");
			
			if ($public_obj->save())
			{
				$public_obj->linkWithNode($root_obj->getNodeId());
				$logtext .= "Created ".$public_obj->getName().".<br/>";
			}
			else
			{
				$logtext .= "Failed to create ".$public_obj->getName().".<br/>";
				$logtext .= $public_obj->error;
				$this->done = false;
			}
			
			
			if ($this->done)
			{
				setSetting("ROOT_NODE_ID",	$root_obj->getNodeId(),	"any");
				setSetting("ANONYMOUS_ID",	$anonymous->id,		"any");
				setSetting("TRANSPORT",		"ajax",			"any");
				setSetting("IMGSIZE",		550,			"any");
				setSetting("THUMBSIZE",		150,			"any");
				setSetting("INSTANTTHUMBS",	"true",			"any");
				setSetting("DEFAULT_THEME",	$this->site,		"any");
				setSetting("DEFAULT_PATH",	"/root/public",		$this->site);
				setSetting("DEFAULT_LANG",	"eng",			$this->site);
				setSetting("TITLE",		"Welcome to MURRiX",	$this->site);
				
				
	
	
				$confdata = "<?\n";
				$confdata .= "\$mysql_address = \"".$this->db_address."\";\n";
				$confdata .= "\$mysql_user = \"".$this->db_username."\";\n";
				$confdata .= "\$mysql_pass = \"".$this->db_password."\";\n";
				$confdata .= "\$mysql_db = \"".$this->db_name."\";\n";
				$confdata .= "\$db_prefix = \"".$this->db_prefix."\";\n";
				$confdata .= "?>";
	
				if (is_writable($abspath))
				{
					$conffile = fopen("$abspath/config.inc.php", "w");
					fwrite($conffile, $confdata);
					fclose($conffile);
					chmod("$abspath/config.inc.php", 0600);
					$logtext .= "Wrote config, $abspath/config.inc.php.<br/>";
				}
				else
				{
					$logtext .= "Unable to write config file.<br/>";
					$logtext .= "Please put the folowing into \"config.inc.php\" and place it in MURRiXs rootpath:<br/>";
					$logtext .= "<br/>";
					$logtext .= nl2br(htmlentities($confdata));
					$logtext .= "<br/>";
				}
				$logtext .= "Installation complete!<br/>";
			}
			else
				$logtext .= "Installation failed!<br/>";
		}
		
		$this->draw($system, $args);
	}
	*/
	function draw(&$system, $args)
	{
		$this->actionPreinstall($system, $args);
	}
}
?>