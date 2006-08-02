<?

class mObject
{
	// PUBLIC FUNCTIONS
	
	function mObject($node_id = 0, $version = 0, $language = "")
	{
		// If no node_id is specified then we just return

		// If node_id is set then load the most recent version for the current language
		if ($node_id > 0)
			$this->loadByNodeId($node_id, $version, $language);
	}

	var $error;
	function getLastError()
	{
		return $this->error;
	}
	
	//	loadByNodeId
	//	Arguments:
	//		$node_id; This is the id of the node to load
	//		$version; This is the version of the object you want to load, 0 means the most recent version
	//		$language; This is the language of the object we want to load, empty means the current language
	function loadByNodeId($node_id, $version = 0, $language = "")
	{
		// Load object with specified arguments
		global $db_prefix;

		if (empty($language))
			$language = $_SESSION['murrix']['language'];

		if ($version == 0)
			$objects = fetch("FETCH node WHERE property:node_id='$node_id' NODESORTBY property:version");
		else
			$objects = fetch("FETCH object WHERE property:node_id='$node_id' AND property:version='$version' AND property:language='$language'");


		if (count($objects) > 0)
		{
			foreach (get_object_vars($objects[0]) as $key => $value)
				$this->$key = $value;
			
			//$this = $objects[0];
		}
		
	}

	function loadByObjectId($object_id)
	{
		$cache_obj = getObjectFromCache($object_id);
		if (!($cache_obj === false))
		{
			foreach (get_object_vars($cache_obj) as $key => $value)
				$this->$key = $value;
			//$this = $cache_obj;
			return true;
		}
	
		// Load a specific object
		global $db_prefix;
		
		$query = "SELECT * FROM `".$db_prefix."objects` WHERE id = '$object_id'";
		if (!($result = mysql_query($query)))
		{
			$this->error = "mObject::loadByObjectId: " . mysql_errno() . " " . mysql_error();
			return false;
		}

		$ret = $this->loadByArray(mysql_fetch_array($result, MYSQL_ASSOC));

		addObjectToCache($this);

		return $ret;
	}

	function loadByArray($data)
	{
		$this->id 		= $data['id'];
		$this->name 		= $data['name'];
		$this->node_id 		= $data['node_id'];
		$this->user_id 		= $data['user_id'];
		$this->rights 		= $data['rights'];
		$this->created 		= $data['created'];
		$this->class_name 	= $data['class_name'];
		$this->version 		= $data['version'];
		$this->language 	= $data['language'];
		$this->icon 		= $data['icon'];

		$this->loadClassIcon();

		return $this->loadVars();
	}

	function loadClassIcon()
	{
		global $db_prefix;
		
		$query = "SELECT default_icon AS icon FROM `".$db_prefix."classes` WHERE name = '$this->class_name'";
		if ($result = mysql_query($query))
		{
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			$this->class_icon = $row['icon'];
		}
	}

	function loadVars()
	{
		global $db_prefix;

		$query = "SELECT vars.*, values.data AS data, values.id AS value_id FROM `".$db_prefix."vars` AS `vars` LEFT JOIN `".$db_prefix."values` AS `values` ON (values.object_id='$this->id' AND vars.id=values.var_id) WHERE vars.class_name='$this->class_name' ORDER BY vars.priority";
		
		if (!($result = mysql_query($query)))
		{
			$this->error = "mObject::loadVars: " . mysql_errno() . " " . mysql_error();
			return false;
		}
		
		$this->vars = array();
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$class_name = "mVar".ucfirst($row['type']);
			$var = new $class_name();

			$row['object_id'] = $this->id;

			$var->SetByArray($row);
			
			$this->vars[$row['name']] = $var;
		}
		
		return true;
	}

	function deleteCurrentVersion()
	{
		global $db_prefix;
		$query = "DELETE FROM `".$db_prefix."objects` WHERE id = '$this->id'";

		$result = mysql_query($query);
		if (!$result)
		{
			$message = "<b>An error occured while deleting</b><br/>";
			$message .= "<b>Table:</b> `".$db_prefix."objects`<br/>";
			$message .= "<b>Query:</b> $query<br/>";
			$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
			$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
			$this->error = $message;
			return false;
		}
		
		foreach ($this->vars as $var)
		{
			if (!$var->Remove())
			{
				$this->error = $var->name." failed!";
				return false;
			}
		}

		$_SESSION['murrix']['querycache'] = array();
		updatePaths($this->getNodeId());
		
		return true;
	}

	function deleteNodeId()
	{
		global $db_prefix;
		$query = "DELETE FROM `".$db_prefix."nodes` WHERE id = '$this->node_id'";

		$result = mysql_query($query);
		if (!$result)
		{
			$message = "<b>An error occured while deleting</b><br/>";
			$message .= "<b>Table:</b> `".$db_prefix."nodes`<br/>";
			$message .= "<b>Query:</b> $query<br/>";
			$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
			$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
			$this->error = $message;
			return false;
		}

		return true;
	}

	function deleteNode()
	{
		$this->deleteMeta();
	
		// Use fetch to get the children of this object
		// Get all versions of this object, and all languanges
		$subobjects = fetch("FETCH object WHERE link:node_top='".$this->getNodeId()."' AND link:type='sub'");

		// Delete all subobjects
		if (count($subobjects) > 0)
		{
			foreach ($subobjects as $subobject)
				$subobject->deleteNode();
		}
		// Get all versions of this object
		$versions = fetch("FETCH object WHERE property:node_id='".$this->getNodeId()."'");
		// Delete all subobjects
		if (count($versions) > 0)
		{
			foreach ($versions as $version)
				$version->deleteCurrentVersion();
		}
		
		// Delete all links
		$links = $this->getLinks();
		foreach ($links as $link)
			$this->unlinkWithNode($link['remote_id'], $link['type'], ($link['direction'] == "top" ? "bottom" : "top"));

		$this->deleteNodeId();
		updatePaths($this->getNodeId());

		return true;
	}

	function duplicate()
	{
		// Create a new object in php, reset version number, reset id and all values id
	}

	function getNewNodeId()
	{
		global $db_prefix;
		
		$datetime = date("Y-m-d H:i:s");
		if (!($result = mysql_query("INSERT INTO `".$db_prefix."nodes` (`id`, `created`) VALUES ('', '$datetime')")))
		{
			$this->error = "mObject::getNewNodeId: " . mysql_errno() . " " . mysql_error();
			return false;
		}

		return mysql_insert_id();
	}

	function save($asis = false)
	{
		// Save a new version of this object
		global $db_prefix;

		if (!$asis)
		{
			if ($this->node_id == 0)
			{
				$this->node_id = $this->getNewNodeId();
				$this->version = 1;
			}
			else
			{
				$versionlist = $this->getVersionNumbers($this->getLanguage());
				if ($versionlist === false || empty($versionlist))
				{
					$this->version = 1;
				}
				else
					$this->version = $versionlist[0]+1;
			}
		
			$this->created = date("Y-m-d H:i:s");

			$this->user_id = (isset($_SESSION['murrix']['user']) ? $_SESSION['murrix']['user']->id : $this->user_id);
		}
		
		$query = "INSERT INTO `".$db_prefix."objects` (name, node_id, user_id, rights, created, class_name, version, language, icon) VALUES('$this->name', '$this->node_id', '$this->user_id', '$this->rights', '$this->created', '$this->class_name', '$this->version', '$this->language', '$this->icon')";

		if (!($result = mysql_query($query)))
		{
			$message = "<b>An error occured while inserting</b><br/>";
			$message .= "<b>Table:</b> objects<br/>";
			$message .= "<b>Query:</b> $query<br/>";
			$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
			$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
			$this->error = $message;
			return false;
			///FIXME: Vi borde ta bort nodidt som skapats.
		}

		$this->id = mysql_insert_id();

		foreach ($this->vars as $var)
		{
			$var->object_id = $this->id;
			$var->value_id = 0;
			$ret = $var->Save();
			if ($ret !== true)
				return $var->name." failed! $ret";
		}

		$this->loadByObjectId($this->id);
		$_SESSION['murrix']['querycache'] = array();
		updatePaths($this->getNodeId());

		return true;
	}
	
	function saveCurrent()
	{
		// Save a new version of this object
		global $db_prefix;

		$query = "UPDATE `".$db_prefix."objects` SET `name`='$this->name', `icon`='$this->icon', `user_id`='$this->user_id',  `rights`='$this->rights' WHERE id = '$this->id'";
		
		if (!($result = mysql_query($query)))
		{
			$message = "<b>An error occured while updateing</b><br/>";
			$message .= "<b>Table:</b> objects<br/>";
			$message .= "<b>Query:</b> $query<br/>";
			$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
			$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
			$this->error = $message;
			return false;
			///FIXME: Vi borde ta bort nodidt som skapats.
		}

		foreach ($this->vars as $var)
		{
			$ret = $var->Save();
			if ($ret !== true)
				return $var->name." failed! $ret";
		}

		delObjectFromCache($this->id);
		unset($_SESSION['murrix']['querycache']);
		$this->loadByObjectId($this->id);
		updatePaths($this->getNodeId());

		return true;
	}
	
	function getVersionNumbers($language = "")
	{
		if ($this->node_id <= 0)
			return 0;

		if (!empty($language))
			$language = "AND language = '$language'";
	
		global $db_prefix;
		
		$query = "SELECT version FROM `".$db_prefix."objects` WHERE node_id = '$this->node_id' $language ORDER BY version DESC";
		if (!($result = mysql_query($query)))
		{
			$this->error = "mObject::getVersionNumbers: " . mysql_errno() . " " . mysql_error();
			return false;
		}

		$versionlist = array();
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			$versionlist[] = $row['version'];

		return $versionlist;
	}
	
	function getPath($allpaths = false)
	{
		// Return main path, all with main first if allpaths is true
		$paths = getPaths($this->node_id, $this->getName());

		if ($allpaths)
			return $paths;

		return $paths[0];
	}

	function getPathInTree($root_path = "")
	{
		if (empty($root_path))
		{
			if (!isset($_SESSION['murrix']['path']) || empty($_SESSION['murrix']['path']))
				$root_path = "/Root";
			else
				$root_path = $_SESSION['murrix']['path'];
		}
	
		$paths = $this->getPath(true);

		if (count($paths) > 0)
		{
			for ($n = 0; $n < count($paths); $n++)
			{
				$pos = strpos($paths[$n], $root_path);
				if ($pos === 0)
					return $paths[$n];
			}

			return $paths[0];
		}
		
		return $this->getPath();
	}

	function checkRecursion($node_id, $parent_id)
	{
		global $db_prefix;
		$query = "SELECT node_top FROM `".$db_prefix."links` WHERE node_bottom = '$parent_id' AND type = 'sub'";
		
		if (!($result = mysql_query($query)))
		{
			$this->error = "mObject::checkRecursion: " . mysql_errno() . " " . mysql_error();
			return false;
		}

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			if ($row['node_top'] == $node_id)
				return true;

			if ($this->checkRecursion($node_id, $row['node_top']))
				return true;
		}

		return false; // false means there were no recursion found
	}

	function linkWithNode($node_id, $type = "sub", $direction = "bottom")
	{
		global $db_prefix;

		// Validate node_id
		if ($node_id <= 0)
		{
			$this->error = "Remote object with node id $node_id, was not found";
			return false;
		}

		if ($node_id == $this->getNodeId())
		{
			$this->error = "Remote object with node id $node_id, was not found";
			return false;
		}

		if ($this->isLinkedTo($node_id, $type))
		{
			$this->error = "A link to remote object already exists";
			return false;
		}

		if ($direction == "bottom")
		{
			if ($this->checkRecursion($this->getNodeId(), $node_id) && $type == "sub")
			{
				$this->error = "Recursion error, link creation denied";
				return false;
			}
			$query = "INSERT INTO `".$db_prefix."links` (node_top, node_bottom, type) VALUES('$node_id', '$this->node_id', '$type')";
		}
		else
		{
			if ($this->checkRecursion($node_id, $this->getNodeId()) && $type == "sub")
			{
				$this->error = "Recursion error, link creation denied";
				return false;
			}
			$query = "INSERT INTO `".$db_prefix."links` (node_top, node_bottom, type) VALUES('$this->node_id', '$node_id', '$type')";
		}

		if (!($result = mysql_query($query)))
		{
			$message = "<b>An error occured while inserting</b><br/>";
			$message .= "<b>Table:</b> ".$db_prefix."links<br/>";
			$message .= "<b>Query:</b> $query<br/>";
			$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
			$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
			$this->error = $message;
			return false;
		}

		if ($type == "sub")
		{
			if ($direction == "bottom")
			{
				$parent = new mObject($node_id);
				$paths = $parent->getPath(true);
			}
			else
			{
				$child = new mObject($node_id);
				$paths = $this->getPath(true);
			}
		}

		$_SESSION['murrix']['querycache'] = array();
		updatePaths($this->getNodeId());
		
		return true;
	}

	function unlinkWithNode($node_id, $type, $direction)
	{
		global $db_prefix;
		
		if ($direction == "bottom")
			$query = "DELETE FROM `".$db_prefix."links` WHERE type = '$type' AND node_top = '$node_id' AND node_bottom = '$this->node_id'";
		else if ($direction == "top")
			$query = "DELETE FROM `".$db_prefix."links` WHERE type = '$type' AND node_top = '$this->node_id' AND node_bottom = '$node_id'";
		else
			$query = "DELETE FROM `".$db_prefix."links` WHERE type = '$type' AND ((node_top = '$this->node_id' AND node_bottom = '$node_id') OR (node_top = '$node_id' AND node_bottom = '$this->node_id'))";

		if (!($result = mysql_query($query)))
		{
			$message = "<b>An error occured while deleting</b><br/>";
			$message .= "<b>Table:</b> ".$db_prefix."links<br/>";
			$message .= "<b>Query:</b> $query<br/>";
			$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
			$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
			$this->error = $message;
			return false;
		}

		$_SESSION['murrix']['querycache'] = array();
		updatePaths($this->getNodeId());
		
		return true;
	}
	
	function deleteLink($id)
	{
		global $db_prefix;
		
		$query = "DELETE FROM `".$db_prefix."links` WHERE `id`='$id'";

		if (!($result = mysql_query($query)))
		{
			$message = "<b>An error occured while deleting</b><br/>";
			$message .= "<b>Table:</b> ".$db_prefix."links<br/>";
			$message .= "<b>Query:</b> $query<br/>";
			$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
			$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
			$this->error = $message;
			return false;
		}

		$_SESSION['murrix']['querycache'] = array();
		updatePaths($this->getNodeId());
		
		return true;
	}

	function isLinkedTo($remote_id, $type = "sub")
	{
		global $db_prefix;

		$query = "SELECT * FROM `".$db_prefix."links` WHERE ((node_top = '$remote_id' AND node_bottom = '".$this->getNodeId()."') OR (node_bottom = '$remote_id' AND node_top = '".$this->getNodeId()."')) AND type = '$type'";
		
		if (!($result = mysql_query($query)))
		{
			$this->error = "mObject::isLinkedTo: " . mysql_errno() . " " . mysql_error();
			return false;
		}

		return (mysql_num_rows($result) > 0);
	}

	function getLinks($node_id = 0, $type = "")
	{
		global $db_prefix;

		if ($node_id == 0)
			$node_id = $this->node_id;
		
		if (!empty($type))
			$type = "AND (type = '$type')";
			
		
		$query = "SELECT id, type, IF(node_top = '$node_id', node_bottom, node_top) AS remote_id, IF(node_top = '$node_id', 'bottom', 'top') AS direction FROM `".$db_prefix."links` WHERE ((node_top = '$node_id') OR (node_bottom = '$node_id')) $type ORDER BY type";
		
		if (!($result = mysql_query($query)))
		{
			$this->error = "mObject::getLinks: " . mysql_errno() . " " . mysql_error();
			return false;
		}

		$linklist = array();
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$linklist[] = $row;
		}

		return $linklist;
	}

	function getAllMeta()
	{
		global $db_prefix;
		$query = "SELECT * FROM `".$db_prefix."meta` WHERE node_id = '$this->node_id'";
		
		$result = mysql_query($query);

		$metadata = array();

		while($row = mysql_fetch_array($result, MYSQL_ASSOC))
			$metadata[] = $row;
			
		return $metadata;
	}

	function getMeta($name, $default = "")
	{
		global $db_prefix;
		$query = "SELECT value FROM `".$db_prefix."meta` WHERE (name = '$name') AND (node_id = '$this->node_id')";
		
		if (!($result = mysql_query($query)))
			return $default;

		if (mysql_num_rows($result) == 0)
			return $default;

		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		return $row['value'];
	}

	function setMeta($name, $value)
	{
		if (empty($value))
			return $this->deleteMeta($name);
		
		global $db_prefix;
		
		$query = "UPDATE `".$db_prefix."meta` SET `value`='$value' WHERE `node_id` = '$this->node_id' AND `name`='$name'";
		
		if (!($result = mysql_query($query)))
		{
			$message = "<b>An error occured while updating</b><br/>";
			$message .= "<b>Table:</b> `".$db_prefix."meta`<br/>";
			$message .= "<b>Query:</b> $query<br/>";
			$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
			$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
			$this->error = $message;
			return false;
		}
		
		if (mysql_affected_rows() == 0)
		{
			$query = "INSERT INTO `".$db_prefix."meta` (`node_id`, `name`, `value`) VALUES('$this->node_id', '$name', '$value')";
	
			if (!($result = mysql_query($query)))
			{
				$message = "<b>An error occured while inserting</b><br/>";
				$message .= "<b>Table:</b> ".$db_prefix."meta`<br/>";
				$message .= "<b>Query:</b> $query<br/>";
				$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
				$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
				$this->error = $message;
				return false;
			}
		}

		return true;
	}

	function deleteMeta($name = "")
	{
		global $db_prefix;
		if (empty($name))
			$query = "DELETE FROM `".$db_prefix."meta` WHERE node_id = '$this->node_id'";
		else
			$query = "DELETE FROM `".$db_prefix."meta` WHERE node_id = '$this->node_id' AND name = '$name'";

		$result = mysql_query($query);
		if (!$result)
		{
			$message = "<b>An error occured while deleting</b><br/>";
			$message .= "<b>Table:</b> `".$db_prefix."meta`<br/>";
			$message .= "<b>Query:</b> $query<br/>";
			$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
			$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
			$this->error = $message;
			return false;
		}
		
		return true;
	}

	function hasRight($action)
	{
		if (isAdmin())
			return true;
			
		$rights = $this->getRights();
		$rights_parts = explode(",", $rights);
		$user_groups = $_SESSION['murrix']['user']->getGroups();
		$user_groups[] = "all";
		
		foreach ($rights_parts as $right)
		{
			list($groupname, $grouprights) = explode("=", $right);
			
			$letter = "u";
			switch ($action)
			{
				case "read":
				$letter = "r";
				break;
				
				case "delete":
				case "edit":
				case "write":
				$letter = "w";
				break;
				
				case "create":
				$letter = "c";
				break;
			}
			
			if (strpos($grouprights, $letter) !== false && in_array($groupname, $user_groups))
				return true;
		}
		
		return false;
	}
	
	var $vars;
	function getVarEdit($varname, $formname)
	{
		// Get var editcode
		if (isset($this->vars[$varname]))
			return $this->vars[$varname]->getEdit($formname);

		$this->error = "mObject::getVarEdit: No variable by the name \"$varname\" found.";
		return false;
	}
	
	function getVarShow($varname)
	{
		// Get var editcode
		if (isset($this->vars[$varname]))
			return $this->vars[$varname]->getShow();

		$this->error = "mObject::getVarEdit: No variable by the name \"$varname\" found.";
		return false;
	}

	function setVarValue($varname, $value)
	{
		// Set var value
		if (isset($this->vars[$varname]))
			return $this->vars[$varname]->setValue($value);

		$this->error = "mObject::setVarValue: No variable by the name \"$varname\" found.";
		return false;
	}

	function getVarValue($varname, $rawvalue = false)
	{
		// Get var value
		if (isset($this->vars[$varname]))
			return $this->vars[$varname]->getValue($rawvalue);

		$this->error = "mObject::getVarValue: No variable by the name \"$varname\" found.";
		return false;
	}

	function getVars()
	{
		// Get the whole varlist
		return $this->vars;
	}
	
	function resolveVarName($varname)
	{
		if (isset($this->vars[$varname]))
			return $this->vars[$varname]->value_id;
			
		return 0;
	}
	
	function checkVarExistance($varname)
	{
		return isset($this->vars[$varname]);
	}

	function resolveVarId($varid)
	{
		foreach ($this->vars as $var)
		{
			if ($var->id == $varid)
				return $var->getName(true);
		}

		return "";
	}

	var $id;
	function getId() { return $this->id; }

	var $node_id;
	function getNodeId() { return $this->node_id; }
	
	var $created;
	function getCreated() { return $this->created; }

	var $version;
	function getVersion() { return $this->version; }
	
	var $class_name;
	function setClassName($class_name) { $this->class_name = $class_name; }
	function getClassName() { return $this->class_name; }

	var $name;
	function setName($name) { $this->name = $name; }
	function getName() { return $this->name; }
	
	var $icon;
	var $class_icon;
	function setIcon($icon) { $this->icon = $icon; }
	function getIcon($class = true)
	{
		if (empty($this->icon) && $this->class_name == "file")
			return getfiletype(pathinfo($this->getVarValue("file"), PATHINFO_EXTENSION));
	
		if (empty($this->icon) && $class)
			return $this->class_icon;
			
		return $this->icon;
	}

	var $user_id;
	function setUserId($user_id) { $this->user_id = $user_id; }
	function getUserId() { return $this->user_id; }
	function getUser() { return new mUser($this->user_id); }
	
	var $rights;
	function setRights($rights) { $this->rights = $rights; }
	function getRights() { return $this->rights; }

	var $language;
	function setLanguage($language) { $this->language = $language; }
	function getLanguage() { return $this->language; }

	function getSerialized()
	{
		$array = array();
		
		$array['id'] = $this->id;
		$array['node_id'] = $this->node_id;
		$array['created'] = $this->created;
		$array['version'] = $this->version;
		$array['class_name'] = $this->class_name;
		$array['name'] = $this->name;
		$array['icon'] = $this->icon;
		$array['class_icon'] = $this->class_icon;
		$array['real_icon'] = $this->getIcon();
		$array['user_id'] = $this->user_id;
		$array['rights'] = $this->rights;
		$array['language'] = $this->language;
		
		// Links
		$array['links'] = $this->getLinks();
		
		// Meta
		$array['meta'] = $this->getAllMeta();
		
		// Vars
		foreach ($this->vars as $var)
			$array['variables'][] = $var->getSerialized();
	}
	
	function setBySerialized($array)
	{
	
	}
}

?>