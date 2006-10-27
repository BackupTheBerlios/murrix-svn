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
		$this->zone = "zone_main";
	}
	
	function Exec(&$system, &$response, $args)
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

					$files = GetSubfiles("$abspath/scripts/install/db");
					foreach ($files as $table)
					{
						$table = basename($table, ".sql");
						if (mysql_query("SELECT * FROM `".$this->db_prefix."$table`"))
						{
							$this->db_log .= "Table ".$this->db_prefix."$table exists.<br/>";
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
			
			$files = GetSubfiles("$abspath/scripts/install/db");
			foreach ($files as $file)
			{
				$query = "DROP TABLE IF EXISTS `".$this->db_prefix.basename($file, ".sql")."`";
				mysql_query($query);
			
				$query = str_replace("%PREFIX%", $this->db_prefix, implode("", file("$abspath/scripts/install/db/$file")));
				
				if (mysql_query($query))
					$this->db_log .= "Imported $file successfully.<br/>";
				else
				{
					$this->db_log .= "Falied to import $file. Error: " . mysql_errno() . " " . mysql_error()."<br/>";
					$this->db_log .= $query;
					$this->done = false;
					break;
				}
			}

			if ($this->done)
			{
				$db_prefix = $this->db_prefix;
				$table = new mTable("classes");
				
				$list = array();
				$list[] = array("name" => "folder",		"default_icon" => "folder");
				$list[] = array("name" => "file_folder",	"default_icon" => "file_folder");
				$list[] = array("name" => "file",		"default_icon" => "file");
				$list[] = array("name" => "comment",		"default_icon" => "comment");
				$list[] = array("name" => "article",		"default_icon" => "article");
				$list[] = array("name" => "link",		"default_icon" => "global");
				$list[] = array("name" => "event",		"default_icon" => "date");
				$list[] = array("name" => "forum_topic",	"default_icon" => "comment");
				$list[] = array("name" => "forum_thread",	"default_icon" => "comment");
				$list[] = array("name" => "message",		"default_icon" => "message");
				$list[] = array("name" => "poll",		"default_icon" => "question");
				$list[] = array("name" => "poll_answer",	"default_icon" => "apply");
				$list[] = array("name" => "news",		"default_icon" => "news");
				$list[] = array("name" => "contact",		"default_icon" => "user");
				$list[] = array("name" => "image_region",	"default_icon" => "murrix");
				
				$failed = false;
				foreach ($list as $item)
				{
					if ($table->insert($item) === false)
					{
						$this->db_log .= "Failed to insert ".$item['name']." into ".$this->db_prefix."classes.<br/>";
						$failed = true;
						$this->done = false;
					}
				}
				
				if (!$failed)
					$this->db_log .= "Inserted data into ".$this->db_prefix."classes.<br/>";
			}

			if ($this->done)
			{
				$db_prefix = $this->db_prefix;
				$table = new mTable("vars");
				
				$list = array();
$list[] = array("class_name" => "folder",	"name" => "description","priority" => "10",	"type" => "text");

$list[] = array("class_name" => "article",	"name" => "text",	"priority" => "10",	"type" => "xhtml",	"required" => true,	"extra" => "Default");

$list[] = array("class_name" => "link",		"name" => "address",	"priority" => "10",	"type" => "url",	"required" => true);

$list[] = array("class_name" => "comment",	"name" => "message",	"priority" => "10",	"type" => "xhtml",	"required" => true,	"extra" => "Simple");

$list[] = array("class_name" => "file_folder",	"name" => "description","priority" => "10",	"type" => "text");

$list[] = array("class_name" => "file",		"name" => "file",	"priority" => "10",	"type" => "file",	"required" => true);
$list[] = array("class_name" => "file",		"name" => "description","priority" => "20",	"type" => "text");

$list[] = array("class_name" => "event", "name" => "date",		"priority" => "10",	"type" => "date",	"required" => true);
$list[] = array("class_name" => "event", "name" => "time",		"priority" => "20",	"type" => "time");
$list[] = array("class_name" => "event", "name" => "reoccuring_yearly",	"priority" => "30",	"type" => "boolean",	"required" => true);
$list[] = array("class_name" => "event", "name" => "reoccuring_monthly","priority" => "40",	"type" => "boolean",	"required" => true);
$list[] = array("class_name" => "event", "name" => "description",	"priority" => "50",	"type" => "xhtml",	"extra" => "Default");
$list[] = array("class_name" => "event", "name" => "calendar_hide",	"priority" => "60",	"type" => "boolean",	"required" => true);

$list[] = array("class_name" => "message",	"name" => "text",	"priority" => "10",	"type" => "xhtml",	"required" => true,	"extra" => "Simple");
$list[] = array("class_name" => "message",	"name" => "attatchment","priority" => "20",	"type" => "node");
$list[] = array("class_name" => "message",	"name" => "sender",	"priority" => "30",	"type" => "hidden",	"extra" => "name");

$list[] = array("class_name" => "forum_topic",	"name" => "description","priority" => "10",	"type" => "text",	"required" => true);
$list[] = array("class_name" => "forum_thread",	"name" => "description","priority" => "10",	"type" => "text",	"required" => true);

$list[] = array("class_name" => "poll",		"name" => "question",	"priority" => "10",	"type" => "text",	"required" => true);
$list[] = array("class_name" => "poll",		"name" => "opendate",	"priority" => "20",	"type" => "date",	"required" => true);
$list[] = array("class_name" => "poll",		"name" => "closedate",	"priority" => "30",	"type" => "date",	"required" => true);
$list[] = array("class_name" => "poll",		"name" => "hide_result","priority" => "40",	"type" => "boolean",	"required" => true);
$list[] = array("class_name" => "poll",		"name" => "alternatives","priority" => "50",	"type" => "array",	"required" => true);

$list[] = array("class_name" => "poll_answer",	"name" => "answer",	"priority" => "10",	"type" => "textline",	"required" => true);

$list[] = array("class_name" => "news",		"name" => "expire",	"priority" => "10",	"type" => "date",	"required" => true);
$list[] = array("class_name" => "news",		"name" => "text",	"priority" => "20",	"type" => "xhtml",	"required" => true,	"extra" => "Default");

$list[] = array("class_name" => "contact",	"name" => "thumbnail",	"priority" => "10",	"type" => "thumbnail",	"extra" => 200);
$list[] = array("class_name" => "contact",	"name" => "fullname",	"priority" => "20",	"type" => "textline");
$list[] = array("class_name" => "contact",	"name" => "birthname",	"priority" => "30",	"type" => "textline");
$list[] = array("class_name" => "contact",	"name" => "nicknames",	"priority" => "40",	"type" => "array");
$list[] = array("class_name" => "contact",	"name" => "gender",	"priority" => "50",	"type" => "selection",	"extra" => "male=male,female=female",	"required" => true);
$list[] = array("class_name" => "contact",	"name" => "emails",	"priority" => "60",	"type" => "array");
$list[] = array("class_name" => "contact",	"name" => "mobilephones","priority" => "70",	"type" => "array");
$list[] = array("class_name" => "contact",	"name" => "homephones",	"priority" => "80",	"type" => "array");
$list[] = array("class_name" => "contact",	"name" => "workphones",	"priority" => "90",	"type" => "array");
$list[] = array("class_name" => "contact",	"name" => "address",	"priority" => "100",	"type" => "text");
$list[] = array("class_name" => "contact",	"name" => "icq",	"priority" => "110",	"type" => "textline");
$list[] = array("class_name" => "contact",	"name" => "msn",	"priority" => "120",	"type" => "textline");
$list[] = array("class_name" => "contact",	"name" => "skype",	"priority" => "130",	"type" => "textline");
$list[] = array("class_name" => "contact",	"name" => "allergies",	"priority" => "140",	"type" => "array");
$list[] = array("class_name" => "contact",	"name" => "other",	"priority" => "150",	"type" => "text");

$list[] = array("class_name" => "image_region",	"name" => "params",	"priority" => "10",	"type" => "textline",	"required" => true,	"extra" => "X,Y,Width,Height");
$list[] = array("class_name" => "image_region",	"name" => "text",	"priority" => "20",	"type" => "text");
$list[] = array("class_name" => "image_region",	"name" => "image_width","priority" => "30",	"type" => "textline",	"required" => true);
$list[] = array("class_name" => "image_region",	"name" => "image_height","priority" => "40",	"type" => "textline",	"required" => true);

				$failed = false;
				foreach ($list as $item)
				{
					if ($table->insert($item) === false)
					{
						$this->db_log .= "Failed to insert ".$item['class_name'].".".$item['name']." into ".$this->db_prefix."vars.<br/>";
						$failed = true;
						$this->done = false;
					}
				}
				
				if (!$failed)
					$this->db_log .= "Inserted data into ".$this->db_prefix."vars.<br/>";
			}
			
			
			
			
			if ($this->done)
			{
				$db_prefix = $this->db_prefix;
				$table = new mTable("initial_meta");
				
				$list = array();
				$list[] = array("class_name" => "file",		"name" => "comment_show_num_per_page",	"value" => "all");
				$list[] = array("class_name" => "file",		"name" => "show_comments",		"value" => 1);
				$list[] = array("class_name" => "file_folder",	"name" => "children_show_num_per_page",	"value" => "all");
				$list[] = array("class_name" => "file_folder",	"name" => "view",			"value" => "thumbnails");
				$list[] = array("class_name" => "news",		"name" => "comment_show_num_per_page",	"value" => "all");
				$list[] = array("class_name" => "news",		"name" => "show_comments",		"value" => 1);
				
				$failed = false;
				foreach ($list as $item)
				{
					if ($table->insert($item) === false)
					{
						$this->db_log .= "Failed to insert ".$item['name']." into ".$this->db_prefix."classes.<br/>";
						$failed = true;
						$this->done = false;
					}
				}
				
				if (!$failed)
					$this->db_log .= "Inserted data into ".$this->db_prefix."initial_meta.<br/>";
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

			/*
			==========
			== Root ==
			==========
			*/
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
				$this->db_log .= "Created ".$root_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$root_obj->getName().".<br/>";
				$this->db_log .= $root_obj->error;
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
			$home_obj->setName("home");
			$home_obj->setIcon("home");
			$home_obj->setRights("all=r");

			$home_obj->setVarValue("description", "This folder contain home folders");
			
			if ($home_obj->save())
			{
				$home_obj->linkWithNode($root_obj->getNodeId());
				$this->db_log .= "Created ".$home_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$home_obj->getName().".<br/>";
				$this->db_log .= $home_obj->error;
				$this->done = false;
			}
			
			/*
			================
			== Users Home ==
			================
			*/
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
				$this->db_log .= "Created ".$users_home_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$users_home_obj->getName().".<br/>";
				$this->db_log .= $users_home_obj->error;
				$this->done = false;
			}
			
			/*
			=================
			== Groups Home ==
			=================
			*/
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
				$this->db_log .= "Created ".$group_home_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$group_home_obj->getName().".<br/>";
				$this->db_log .= $group_home_obj->error;
				$this->done = false;
			}
			
			/*
			================
			== Admin Home ==
			================
			*/
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
				$this->db_log .= "Created ".$adminhome_obj->getName().".<br/>";
				
				$administrator->home_id = $adminhome_obj->getNodeId();
				$administrator->save();
			}
			else
			{
				$this->db_log .= "Failed to create ".$adminhome_obj->getName().".<br/>";
				$this->db_log .= $adminhome_obj->error;
				$this->done = false;
			}
			
			/*
			=================
			== Admins Home ==
			=================
			*/
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
				$this->db_log .= "Created ".$adminshome_obj->getName().".<br/>";
				
				$administrator_group->home_id = $adminshome_obj->getNodeId();
				$administrator_group->save();
			}
			else
			{
				$this->db_log .= "Failed to create ".$adminshome_obj->getName().".<br/>";
				$this->db_log .= $adminshome_obj->error;
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
			$public_obj->setName("public");
			$public_obj->setRights("all=r");

			$public_obj->setVarValue("description", "This folder is readable by anyone");
			
			if ($public_obj->save())
			{
				$public_obj->linkWithNode($root_obj->getNodeId());
				$this->db_log .= "Created ".$public_obj->getName().".<br/>";
			}
			else
			{
				$this->db_log .= "Failed to create ".$public_obj->getName().".<br/>";
				$this->db_log .= $public_obj->error;
				$this->done = false;
			}
			
			
			if ($this->done)
			{
				setSetting("ROOT_NODE_ID",	$root_obj->getNodeId(),	"any");
				setSetting("ANONYMOUS_ID",	$anonymous->id,		"any");
				setSetting("IMGSIZE",		640,			"any");
				setSetting("THUMBSIZE",		150,			"any");
				setSetting("INSTANTTHUMBS",	"true",			"any");
				setSetting("DEFAULT_THEME",	$this->site,		"any");
				setSetting("DEFAULT_PATH",	"/root/public",		$this->site);
				setSetting("DEFAULT_LANG",	"eng",			$this->site);
				setSetting("TITLE",		"Welcome to MURRiX",	$this->site);
				
				
	
				/* ======================================================================================= */
	
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
			else
				$this->db_log .= "Installation failed!<br/>";
		}
		
		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		global $wwwpath, $abspath;
		ob_start();
		include(gettpl("install/stage".$args['stage']));
		$system->setZoneData($this->zone, utf8e(ob_get_end()));
	}
}
?>