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

		///FIXME; Make the sql-statements use another language if the specified is not found


		if ($version == 0)
			$objects = fetch("FETCH node WHERE property:node_id='$node_id' NODESORTBY property:version");
			//$query = "SELECT objects.* FROM `".$db_prefix."objects` AS `objects` WHERE objects.language = '$language' AND objects.node_id = '$node_id' ORDER BY objects.version DESC LIMIT 1";
		else
			$objects = fetch("FETCH object WHERE property:node_id='$node_id' AND property:version='$version' AND property:language='$language'");
			//$query = "SELECT objects.* FROM `".$db_prefix."objects` AS `objects` WHERE objects.language = '$language' AND objects.node_id = '$node_id' AND objects.version = '$version'";


		$this = $objects[0];
/*
		if (!($result = mysql_query($query)))
		{
			$this->error = "mObject::loadByNodeId: " . mysql_errno() . " " . mysql_error();
			return false;
		}

		if (mysql_num_rows($result) == 0)
		{
			$this->error = "mObject::loadByNodeId: No object found.";
			return false;
		}
		
		return $this->loadByArray(mysql_fetch_array($result, MYSQL_ASSOC));*/
	}

	function loadByObjectId($object_id)
	{
		// Load a specific object
		global $db_prefix;
		
		$query = "SELECT * FROM `".$db_prefix."objects` WHERE id = '$object_id'";
		if (!($result = mysql_query($query)))
		{
			$this->error = "mObject::loadByObjectId: " . mysql_errno() . " " . mysql_error();
			return false;
		}

		return $this->loadByArray(mysql_fetch_array($result, MYSQL_ASSOC));
	}

	function loadByArray($data)
	{
		$this->id 		= $data['id'];
		$this->name 		= $data['name'];
		$this->node_id 		= $data['node_id'];
		$this->creator 		= $data['creator'];
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

		$_SESSION['murrix']['querycache'] = array();
		
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

		// Delete all links
		$links = $this->getLinks();
		foreach ($links as $link)
			$this->unlinkWithNode($link['remote_id'], $link['type'], ($link['direction'] == "top" ? "bottom" : "top"));

		// Get all versions of this object
		$versions = fetch("FETCH object WHERE property:node_id='".$this->getNodeId()."'");

		// Delete all subobjects
		if (count($versions) > 0)
		{
			foreach ($versions as $version)
				$version->deleteCurrentVersion();
		}

		$this->deleteNodeId();
		$_SESSION['murrix']['querycache'] = array();

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

	function save()
	{
		// Save a new version of this object
		global $db_prefix;

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

		$datetime = date("Y-m-d H:i:s");

		$user_id = (isset($_SESSION['murrix']['user']) ? $_SESSION['murrix']['user']->getNodeId() : 0);
		
		$query = "INSERT INTO `".$db_prefix."objects` (name, node_id, creator, created, class_name, version, language, icon) VALUES('$this->name', '$this->node_id', '$user_id', '$datetime', '$this->class_name', '$this->version', '$this->language', '$this->icon')";

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
		clearPathCache();

		return true;
	}
	
	function saveCurrentVersion()
	{
		// Save the current version of this object
		
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
		///FIXME: Maybe cache this somehow
		// Return main path, all with main first if allpaths is true
		$paths = getPaths($this->node_id);

		if ($allpaths)
		{
			if ($paths === -1)
				return array("/".$this->getName());
		
			for ($n = 0; $n < count($paths); $n++)
				$paths[$n] .= "/".$this->getName();
				
			return $paths;
		}

		if ($paths === -1)
			return "/".$this->getName();

		return $paths[0]."/".$this->getName();
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
	
		$paths = $this->getValidPaths("read");

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

	function addPath($path)
	{
		// Create a link with the new path
		$node_id = resolvePath($path);
		if ($node_id > 0)
			return $this->linkWithNode($node_id);

		$this->error = "mObject::addPath: Could not resolve \"$path\".";
		return false;
	}

	function delPath($path)
	{
		// Remove a link the object specified by the path
		$node_id = resolvePath($path);
		if ($node_id > 0)
			return $this->unlinkWithNode($node_id, "sub", "bottom");

		$this->error = "mObject::delPath: Could not resolve \"$path\".";
		return false;
	}

	function linkWithNode($node_id, $type = "sub", $direction = "bottom")
	{
		global $db_prefix;
		///FIXME: Validate node_id

		if ($direction == "bottom")
			$query = "INSERT INTO `".$db_prefix."links` (node_top, node_bottom, type) VALUES('$node_id', '$this->node_id', '$type')";
		else
			$query = "INSERT INTO `".$db_prefix."links` (node_top, node_bottom, type) VALUES('$this->node_id', '$node_id', '$type')";

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

				foreach ($paths as $path)
					addToPathCache("$path/".$this->getName(), $this->node_id);
			}
			else
			{
				$child = new mObject($node_id);
				$paths = $this->getPath(true);

				foreach ($paths as $path)
					addToPathCache("$path/".$child->getName(), $node_id);
			}
		}

		$_SESSION['murrix']['querycache'] = array();
		
		return true;
	}

	function unlinkWithNode($node_id, $type, $direction)
	{
		global $db_prefix;

		if ($type == "sub")
		{
			clearPathCache();
			/*
			if ($direction == "bottom")
			{
				$parent = new mObject($node_id);
				$paths = $parent->getPath(true);

				foreach ($paths as $path)
					deleteFromPathCache($path);
			}
			else if ($direction == "top")
			{
				$child = new mObject($node_id);
				$paths = $this->getPath(true);

				foreach ($paths as $path)
					deleteFromPathCache($path);
			}*/
		}
		
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
		
		return true;
	}

	function getLinks($node_id = 0)
	{
		global $db_prefix;

		if ($node_id == 0)
			$node_id = $this->node_id;
		
		$query = "SELECT type, IF(node_top = '$node_id', node_bottom, node_top) AS remote_id, IF(node_top = '$node_id', 'bottom', 'top') AS direction FROM `".$db_prefix."links` WHERE (node_top = '$node_id') OR (node_bottom = '$node_id')";
		
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
		$this->deleteMeta($name);

		if (empty($value))
			return true;

		global $db_prefix;
			
		$query = "INSERT INTO `".$db_prefix."meta` (node_id, name, value) VALUES('$this->node_id', '$name', '$value')";

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

	function getValidPaths($action, $classes = null)
	{
		// Check if the right is set for the current user
		$path_list = $this->getPath(true);

		$valid_paths = array();
	
		foreach ($path_list as $path)
		{
			$hasright = false;
			$path_parts = explode("/", $path);
			array_shift($path_parts);
		
			$newpath = "";
			
			for ($n = 0; $n < count($path_parts); $n++)
			{
				$newpath .= "/".$path_parts[$n];
				$path_node = resolvePath($newpath);
				
				if (@is_array($_SESSION['murrix']['rights']['allow'][$action]))
				{
					for ($i = 0; $i < count($_SESSION['murrix']['rights']['allow'][$action]); $i++)
					{
						$node_id = $_SESSION['murrix']['rights']['allow'][$action][$i];
	
						if ($path_node == $node_id)
						{
							if ($action != "create_subnodes")
								$hasright = true;
							else
							{
								$create_classes = $_SESSION['murrix']['rights']['allow']['create_subnodes_classes'][$i];
								if (empty($create_classes))
									$hasright = true;
								else
								{
									if (empty($classes))
									{
										$hasright = true;
									}
									else
									{
										foreach ($classes as $class)
										{
											if (in_array($class, $create_classes))
											{
												$hasright = true;
												break;
											}
										}
									}
								}
							}
						}
					}
				}
				
				if (@is_array($_SESSION['murrix']['rights']['allowown'][$action]) && $object->getCreator() == $_SESSION['murrix']['user']->id)
				{
					if (in_array($path_node, $_SESSION['murrix']['rights']['allowown'][$action]))
						$hasright = true;
				}
				
				if (@is_array($_SESSION['murrix']['rights']['deny'][$action]))
				{
					if (in_array($path_node, $_SESSION['murrix']['rights']['deny'][$action]))
						$hasright = false;
				}
			}

			if ($hasright)
				$valid_paths[] = $path;
		}

		return $valid_paths;
	}

	function hasRight($action, $classes = null)
	{
		$valid_paths = $this->getValidPaths($action, $classes);
		return (count($valid_paths) > 0);
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
		if (empty($this->icon) && $class)
			return $this->class_icon;
			
		return $this->icon;
	}

	var $creator;
	function setCreator($creator) { $this->creator = $creator; }
	function getCreator() { return $this->creator; }

	var $language;
	function setLanguage($language) { $this->language = $language; }
	function getLanguage() { return $this->language; }

}


function fetch($query, $debug = false)
{//$_SESSION['debug2']++;// .= $query;


	if (isset($_SESSION['murrix']['querycache'][$query]) && !$debug)
		return $_SESSION['murrix']['querycache'][$query];

	if ($debug)
		echo "$query<br>";

	//$time = microtime_float();
		
	$query2 = $query;
	$commands = array("FETCH", "WHERE", "NODESORTBY", "SORTBY");
	$cmdstr = implode("|", $commands);

	$links = false;
	$vars = 0;
	$return = "object";
	$sort = array();
	$vars_array = array();

	$nodesortby = "";
	
	$klar = false;
	while (!$klar)
	{
		foreach ($commands as $ord)
		{
			if (preg_match("/^[ ]*($ord) (.+?)( ($cmdstr|$)|$)/", $query, $matches))
			{
				switch ($matches[1])
				{
					case "FETCH":
						switch (trim($matches[2]))
						{
						case "count":
							$select = "SELECT count(objects.id) AS count ";
							$return = "count";
							break;

						case "node":
							$select = "SELECT objects.id AS id ";
							$return = "node";
							break;
							
						case "object":
						default:
							$select = "SELECT objects.id AS id ";
							$return = "object";
							break;
						}
						break;

					case "WHERE":
						$org_where = trim($matches[2]);
						if (preg_match_all("/[ ]*(.+?)( AND| OR|$)/", $org_where, $wmatches))
						{
							$wmatches = $wmatches[1];
							//PrintPre($wmatches);
							foreach ($wmatches AS $match)
							{
								$match = trim($match);
								if ($match{0} == "(")
									$match = substr($match, 1, strlen($match)-1);
								
								$parts = explode(":", $match, 2);

								$invert = "";
								if ($parts[0]{0} == "!")
								{
									$invert = "!";
									$parts[0] = substr($parts[0], 1, strlen($parts[0])-1);
								}

								switch ($parts[0])
								{
								case "property":
									$org_where = str_replace($match, "$invert(objects.".$parts[1].")", $org_where);
									break;

								case "var":
									$parts2 = explode("=", $parts[1]);
									
									if (!isset($vars_array[$parts2[0]]))
									{
										$vars++;
										$vars_array[$parts2[0]] = $vars;
									}
									$num = $vars_array[$parts2[0]];
									$org_where = str_replace($match, "values$num.data$invert=".$parts2[1], $org_where);
									break;

								case "link":
									$parts2 = explode("=", $parts[1]);
									switch ($parts2[0])
									{
									case "node_top":
										$org_where = str_replace($match, "$invert(links.node_top=".$parts2[1]." AND links.node_bottom=objects.node_id)", $org_where);
										break;

									case "node_bottom":
										$org_where = str_replace($match, "$invert(links.node_bottom=".$parts2[1]." AND links.node_top=objects.node_id)", $org_where);
										break;

									case "type":
										$org_where = str_replace($match, "$invert(links.type=".$parts2[1].")", $org_where);
										break;

									case "node_id":
									default:
										$org_where = str_replace($match, "$invert((links.node_bottom=".$parts2[1]." AND links.node_top=objects.node_id) OR (links.node_top=".$parts2[1]." AND links.node_bottom=objects.node_id))", $org_where);
										break;
									}
									$links = true;
									break;
								}
							}
						}

						$where_more = "";
						foreach ($vars_array as $key => $value)
						{
							$where_more .= "(values$value.object_id=objects.id AND values$value.var_id=vars$value.id AND vars$value.name='$key') AND ";
						}
						
						$where = "WHERE $where_more ($org_where)";
						break;

					case "NODESORTBY":
						$org_sort = trim($matches[2]);
						if (preg_match_all("/[ ]*(.+?)(,|$)/", $org_sort, $wmatches))
						{
							$wmatches = $wmatches[1];
							
							foreach ($wmatches AS $match)
							{
								$match = trim($match);

								$parts = explode(":", $match, 2);

								$invert = " DESC";
								if ($parts[0]{0} == "!")
								{
									$invert = " ASC";
									$parts[0] = substr($parts[0], 1, strlen($parts[0])-1);
								}

								switch ($parts[0])
								{
								case "property":
									$org_sort = str_replace($match, "objects.".$parts[1].$invert, $org_sort);
									break;
								}
							}
						}
							$nodesortby = "ORDER BY $org_sort";
						break;

					case "SORTBY":
						$org_sort = trim($matches[2]);
						if (preg_match_all("/[ ]*(.+?)(,|$)/", $org_sort, $wmatches))
						{
							$wmatches = $wmatches[1];
							
							foreach ($wmatches AS $match)
							{
								$match = trim($match);

								$parts = explode(":", $match, 2);

								$invert = false;
								if ($parts[0]{0} == "!")
								{
									$invert = true;
									$parts[0] = substr($parts[0], 1, strlen($parts[0])-1);
								}

								switch ($parts[0])
								{
								case "property":
									$sort[] = array("property:".$parts[1], $invert);
									//$org_sort = str_replace($match, "objects.".$parts[1].($invert ? "" : " DESC"), $org_sort);
									break;
									
								case "var":
									$sort[] = array($parts[1], $invert);
									break;
								}
							}
						}
						//if (!empty($org_sort))
						//	$sortby = "ORDER BY $org_sort";
						break;
				}

				$len = strlen($matches[0]) - strlen($matches[3]);
				$query = trim(substr($query, $len, strlen($query)-$len));
			}
			else
			{
				if (empty($query))
					$klar = 1;
				else
				{
					echo "nåt galet hände..\n";
					echo $query;
				}
			}
		}
	}
	global $db_prefix;

	$from = "FROM `".$db_prefix."objects` AS `objects`";
	
	for ($n = 1; $n <= $vars; $n++)
		$from .= ", `".$db_prefix."vars` AS `vars$n`, `".$db_prefix."values` AS `values$n`";
		
	if ($links)
		$from .= ", `".$db_prefix."links` AS `links`";

	$sql = "$select $from $where $nodesortby";

	$result = mysql_query($sql) or die("fetch " . mysql_errno() . " " . mysql_error());

	if ($return == "count")
	{
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		//$_SESSION['debug'] += microtime_float()-$time;
		$_SESSION['murrix']['querycache'][$query2] = $row['count'];
		return $row['count'];
	}

	$objects = array();
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$object = new mObject();
		if (!$object->loadByObjectId($row['id']))
			echo $object->getLastError();
		$objects[] = $object;
	}
	
	if ($return == "node")
	{
		$nodes = array();
		$node_list = array();
		foreach ($objects as $object)
		{
			// We have a prior version already
			if (isset($node_list[$object->getNodeId()]))
			{
				if ($node_list[$object->getNodeId()] != $_SESSION['murrix']['language']) // Not a perfect match
				{
					if ($object->getLanguage() == $_SESSION['murrix']['language']) // We have a better match
					{
						$nodes[$object->getNodeId()] = $object;
						$node_list[$object->getNodeId()] = $object->getLanguage();
					}
				}
				/*else // Perfect language match
				{
					if ($node_list[$object->getNodeId()]->version < $object->version)// Do we have a better version
					{
						$nodes[$object->getNodeId()] = $object;
						$node_list[$object->getNodeId()] = $object->getLanguage();
					}
				}*/
			}
			else // We have no prior match
			{
				$nodes[$object->getNodeId()] = $object;
				$node_list[$object->getNodeId()] = $object->getLanguage();
			}
		}

		$objects = array_values($nodes);
	}
	
	if (!empty($sort))
	{
		$sort = array_reverse($sort);
		foreach ($sort as $sortby)
		{
			$compare = ReturnCmpFunc($sortby[0], $sortby[1]);
			usort(&$objects, $compare);
		}
	}
	
	$_SESSION['murrix']['querycache'][$query2] = $objects;
	return $objects;
}

function ReturnCmpFunc($sortby, $invert)
{
	$x = ($invert ? '$b,$a' : '$a,$b');

	switch ($sortby)
	{
	case "property:name":
		return create_function($x, "return strnatcasecmp(\$a->getName(), \$b->getName());");
	case "property:class":
		return create_function($x, "return strnatcasecmp(\$a->getClassName(), \$b->getClassName());");
	case "property:language":
		return create_function($x, "return strnatcasecmp(\$a->getLanguage(), \$b->getLanguage());");
	case "property:icon":
		return create_function($x, "return strnatcasecmp(\$a->getIcon(), \$b->getIcon());");
	case "property:version":
		return create_function($x, "return (\$a->getVersion() < \$b->getVersion());");
	case "property:created":
		return create_function($x, "return date_compare(\$a->getCreated(), \$b->getCreated());");
	case "property:creator":
		return create_function($x, "return (\$a->getCreator() < \$b->getCreator());");
	///FIXME: Maybe this should be done in a function in the var-class
	//case "var:date":
	//	return create_function($x, "return date_compare(\$a->GetValue(\"$sortby\"), \$b->GetValue(\"$sortby\"));");
	}
	
	return create_function($x, "return strnatcasecmp(\$a->getVarValue(\"$sortby\"), \$b->getVarValue(\"$sortby\"));");
}

function addToPathCache($path, $node_id)
{
	global $db_prefix;

	$prev_id = getFromPathCache($path);

	if ($prev_id != 0)
	{
		if ($prev_id == $node_id)
			return true;
		else
			deleteFromPathCache($path);
	}
	
	$query = "INSERT INTO `".$db_prefix."pathcache` (node_id, path) VALUES('$node_id', '$path')";
					
	if (!($result = mysql_query($query)))
	{
		$message = "<b>An error occured while inserting</b><br/>";
		$message .= "<b>Table:</b> ".$db_prefix."pathcache`<br/>";
		$message .= "<b>Query:</b> $query<br/>";
		$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
		$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
		echo $message;
		return false;
	}
	$_SESSION['murrix']['pathcache'][$path] = $node_id;
	return true;
}

function clearPathCache()
{
	global $db_prefix;
	$query = "DELETE FROM `".$db_prefix."pathcache`";

	$result = mysql_query($query);
	if (!$result)
	{
		$message = "<b>An error occured while deleting</b><br/>";
		$message .= "<b>Table:</b> `".$db_prefix."pathcache`<br/>";
		$message .= "<b>Query:</b> $query<br/>";
		$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
		$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
		echo $message;
		return false;
	}

	$_SESSION['murrix']['pathcache'] = array();
	
	return true;
}
function deleteFromPathCache($path)
{
	global $db_prefix;
	$query = "DELETE FROM `".$db_prefix."pathcache` WHERE path = '$path'";

	$result = mysql_query($query);
	if (!$result)
	{
		$message = "<b>An error occured while deleting</b><br/>";
		$message .= "<b>Table:</b> `".$db_prefix."pathcache`<br/>";
		$message .= "<b>Query:</b> $query<br/>";
		$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
		$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
		echo $message;
		return false;
	}

	if (isset($_SESSION['murrix']['pathcache']))
	{
		if (!empty($_SESSION['murrix']['pathcache']) && isset($_SESSION['murrix']['pathcache'][$path]))
			unset($_SESSION['murrix']['pathcache'][$path]);
	}
	
	return true;
}

function getFromPathCache($path)
{
	global $db_prefix;
	
	if (empty($_SESSION['murrix']['pathcache']))
	{
		$query = "SELECT node_id, path FROM `".$db_prefix."pathcache`";
	
		if (!($result = mysql_query($query)))
			return 0;

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			$_SESSION['murrix']['pathcache'][$row['path']] = $row['node_id'];
	
	}

	if (isset($_SESSION['murrix']['pathcache'][$path]))
		return $_SESSION['murrix']['pathcache'][$path];
	
	$query = "SELECT node_id FROM `".$db_prefix."pathcache` WHERE (path = '$path')";

	if (!($result = mysql_query($query)))
		return 0;

	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	$_SESSION['murrix']['pathcache'][$path] = $row['node_id'];
	
	return $row['node_id'];
}

function resolvePath($path, $language = "")
{
	global $db_prefix;

	$cache_id = getFromPathCache($path);

	if (!empty($cache_id))
		return $cache_id;

	global $root_id;
	
	// Nothing found i cache
	if (empty($path)) // We are att the root
	{
		return -2;
	}

	$parent_id = resolvePath(GetParentPath($path), $language);

	if ($parent_id == -2)
	{
		addToPathCache($path, $root_id);
		return $root_id;
	}
	else if ($parent_id == -1)
		return -1;

	$name = basename($path);

	if (empty($language))
		$language = $_SESSION['murrix']['language'];
		
	///FIXME: Language should default to another language if the current is not found	
	$query = "SELECT objects.node_id AS node_id FROM `".$db_prefix."objects` AS `objects`, `".$db_prefix."links` AS `links` WHERE objects.name = '$name' AND links.type = 'sub' AND links.node_top = '$parent_id' AND links.node_bottom = objects.node_id ORDER BY objects.version ASC LIMIT 1";

	$result = mysql_query($query) or die("resolvePath: " . mysql_errno() . " " . mysql_error());

	if (mysql_num_rows($result) == 0)
		return -1;
	else
	{
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$node_id = $row['node_id'];
	}
	
	addToPathCache($path, $node_id);
	return $node_id;
}

function getPaths($node_id, $depth = 0)
{
	$time = microtime_float();
	
	$array = array();


	if (isset($_SESSION['murrix']['pathcache']))
	{
		if (is_array($_SESSION['murrix']['pathcache']))
		{
			foreach ($_SESSION['murrix']['pathcache'] as $key => $value)
			{
				if ($value == $node_id)
					array_push($array, GetParentPath($key));
			}
		}
	}
	
	if (count($array) > 0)
	{
		//$_SESSION['debug'] += microtime_float()-$time;
		return $array;
	}
	
	global $db_prefix, $root_id;

	$query = "SELECT DISTINCT(objects.language) AS language, objects.node_id AS node_id, objects.name AS name FROM `".$db_prefix."objects` AS `objects`, `".$db_prefix."links` AS `links` WHERE links.type='sub' AND links.node_top=objects.node_id AND links.node_bottom='$node_id' GROUP BY objects.node_id, objects.language, objects.version DESC, objects.name";

	$result = mysql_query($query) or die("getPaths: " . mysql_errno() . " " . mysql_error());

	$parents = array();
	$rows = array();
	if (mysql_num_rows($result) == 0)
	{
		//$_SESSION['debug'] += microtime_float()-$time;
		return -1;
	}
	else
	{
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			$rows[] = $row;

		$nodes = array();
		$node_list = array();
		foreach ($rows as $row)
		{
			// We have a prior version already
			if (isset($node_list[$row['node_id']]))
			{
				if ($node_list[$row['node_id']] != $_SESSION['murrix']['language']) // Not a perfect match
				{
					if ($row['language'] == $_SESSION['murrix']['language']) // We have a better match
					{
						$nodes[$row['node_id']] = $row;
						$node_list[$row['node_id']] = $row['language'];
					}
				}
			}
			else // We have no prior match
			{
				$nodes[$row['node_id']] = $row;
				$node_list[$row['node_id']] = $row['language'];
			}
		}

		$nodes = array_values($nodes);

		foreach ($nodes as $node)
		{
			$parent->name = $node['name'];
			$parent->node_id = $node['node_id'];
			$parents[] = $parent;
		}
	}

	if (count($parents) == 0)
	{
		//$_SESSION['debug'] += microtime_float()-$time;
		return -1;
	}

	foreach ($parents as $parent)
	{
		$parent_id = $parent->node_id;
		$parent_name = "/".$parent->name;

		$result_array = getPaths($parent_id, $depth+1);

		if ($result_array === -1)
			$array[] = $parent_name;
		else
		{
			foreach ($result_array as $path)
				$array[] = $path.$parent_name;
		}
	}

	//$_SESSION['debug'] += microtime_float()-$time;
	return $array;
}

function getClassList()
{
	global $db_prefix;
	
	$query = "SELECT name FROM `".$db_prefix."classes` ORDER BY name";
	$result = mysql_query($query) or die("getClassList: " . mysql_errno() . " " . mysql_error());

	$list = array();
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		$list[] = $row['name'];

	return $list;
}

?>