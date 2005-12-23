<?

class mObject
{
	var $id;
	var $name;
	var $class_name;
	var $class_icon;
	var $creator;
	var $created;
	var $modified;
	
	var $links;
	var $children;
	var $class;
	
	var $path;
	var $paths;
	
	var $vars;
	var $vars_name;
	
	function mObject($id = 0)
	{
		if ($id > 0)
			$this->SetById($id);
	}
	
	function SetByPath($path)
	{
		$object_id = ResolvePath($path);

		if ($object_id < 0)
			return false;
			
		if ($object_id > 0)
			$this->SetById($object_id);
			
		$this->path = $path;
		
		return true;
	}
	
	function SetById($id)
	{
		if (isset($_SESSION['murrix']['objects'][$id]))
		{
			$this = $_SESSION['murrix']['objects'][$id];
			return;
		}

		// Load stuff from DB
		$query = "SELECT * FROM `objects` WHERE id = '$id'";
		$result = mysql_query($query) or die("mObject::SetById: " . mysql_errno() . " " . mysql_error());
		$this->SetByArray(mysql_fetch_array($result, MYSQL_ASSOC));

		$_SESSION['murrix']['objects'][$id] = $this;
	}
	
	function SetByArray($array)
	{
		$this->id = $array['id'];
		$this->name = $array['name'];
		$this->class_name = $array['class_name'];
		$this->creator = $array['creator'];
		$this->created = $array['created'];
		$this->modified = $array['modified'];
		
		//$this->class = new mClass($this->class_name);
		
		$this->InitVars();
	}
	
	function InitVars()
	{
		//db_connect();
		$query = "SELECT icon FROM `classes` WHERE name = '$this->class_name' LIMIT 1";
		$result = mysql_query($query) or die("mObject::SetByArray: " . mysql_errno() . " " . mysql_error());
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$this->class_icon = $row['icon'];
		
		$this->vars = mVar::GetVarsForObject($this->id, $this->class_name);
		
		unset($this->vars_name);
		foreach ($this->vars as $var)
			$this->vars_name[$var->GetName(true)] = $var;
		
	}
	
	function GetPath()
	{
		if (empty($this->path))
		{
			$paths = $this->GetPaths();
			$this->path = $paths[0];
		}
		
		return $this->path;
	}
	
	function GetPaths()
	{
		if (empty($this->paths))
			$this->paths = GetPaths($this->id);
	
		return $this->paths;
	}
	
	function GetRelatedHash($hashmap = array())
	{
		$sort = null;
		$sort_invert = false;
	
		$id = $this->id;
/* EXAMPLE
		$hashmap = array(
				array("classes", array("comment", "events"), true),
				array("sort", array("comment", "events"), true),
				array("type", array("sub", "partner")),
				array("side", "top"));
*/	

		$where = "";
		$main_where = " ((relations.obj1_id = '$id' AND relations.obj2_id = objects.id) OR (relations.obj2_id = '$id' AND relations.obj1_id = objects.id))";
		foreach ($hashmap as $hash)
		{
			$invert = (isset($hash[2]) ? $hash[2] : false);
			
			switch ($hash[0])
			{
				case "classes":
					foreach ($hash[1] as $class)
					{
						if ($invert)
							$where .= " AND objects.class_name != '$class'";
						else
							$where .= " AND objects.class_name = '$class'";
					}
					break;
					
				case "creator":
					foreach ($hash[1] as $creator)
					{
						if ($invert)
							$where .= " AND objects.creator != '$creator'";
						else
							$where .= " AND objects.creator = '$creator'";
					}
					break;
					
				case "type":
					foreach ($hash[1] as $type)
					{
						if ($invert)
							$where .= " AND relations.type != '$type'";
						else
							$where .= " AND relations.type = '$type'";
					}
					break;
					
				case "side":
					if ($hash[1] == "bottom")
						$main_where = " relations.obj1_id = '$id' AND relations.obj2_id = objects.id";
					else if ($hash[1] == "top")
						$main_where = " relations.obj2_id = '$id' AND relations.obj1_id = objects.id";
					break;

				case "limit":
					$limit = $hash[1][0];
					break;
					
				case "sort":
					$sort = $hash[1];
					$sort_invert = $invert;
					break;
			}
		}
		//SELECT objects.* 
		$query = "
			SELECT objects.id as id 
			FROM `relations`, `objects`
			WHERE $main_where $where ORDER BY objects.class_name, objects.name";
		
		$result = mysql_query($query) or die("mObject::GetRelatedHash: " . mysql_errno() . " " . mysql_error());
	
		$relations = array();
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$object = new mObject();
			//$object->SetByArray($row);
			$object->SetById($row['id']);
			
			if ($type == "sub" && $side_of_link == 1)
				$object->path = $this->GetPath()."/".$object->name;
			
			$relations[] = $object;
		}
				
		if ($sort != null)
		{
			$sort = array_reverse($sort);
			foreach ($sort as $sortby)
			{
				$compare = $this->ReturnCmpFunc($sortby, $sort_invert);
				usort(&$relations, $compare);
			}
		}

		if (!empty($limit))
			$relations = array_slice($relations, 0, $limit);
	
		return $relations;
	}
	
	function ReturnCmpFunc($sortby, $invert)
	{
		$x = ($invert ? '$b,$a' : '$a,$b');
	
		switch ($sortby)
		{
		case "name":
			return create_function($x, "return strnatcasecmp(\$a->name, \$b->name);");
		case "class":
			return create_function($x, "return strnatcasecmp(\$a->class_name, \$b->class_name);");
		case "created":
			return create_function($x, "return strnatcasecmp(\$a->created, \$b->created);");
		case "creator":
			return create_function($x, "return (\$a->creator < \$b->creator);");
		case "date":
			return create_function($x, "return date_compare(\$a->GetValue(\"$sortby\"), \$b->GetValue(\"$sortby\"));");
		}
		return create_function($x, "return strnatcasecmp(\$a->GetValue(\"$sortby\"), \$b->GetValue(\"$sortby\"));");
	}
	
	function GetRelatedWithRights($relations)
	{
		$relations2 = array();
		foreach ($relations as $object)
		{
			$paths = $object->GetPaths();
			if (count($paths) > 0)
				$relations2[] = $object;
		}
		return $relations2;
	}
	
	function GetIcon()
	{
		$icon = $this->GetValue("icon", true);
		if ($icon == "NULL" || !$icon)
			return (empty($this->class_icon) ? "folder" : $this->class_icon);
		
		return $icon;
	}
	
	function GetValue($name, $raw = false)
	{
		$name = strtolower($name);
		
		if (is_array($this->vars))
		{
			foreach ($this->vars as $var)
			{
				if ($var->GetName(true) == $name)
					return $var->GetValue($raw);
			}
		}
	//	if (isset($this->class->vars_name[$name]))
	//		return $this->class->vars_name[$name]->GetValue($this->id, $raw);
		
		return false;
	}
	
	function GetVars($indexbyname = false)
	{
		//if (!isset($this->class))
		//	return array();
	
		if ($indexbyname)
			return $this->vars_name;
		
		return $this->vars;
	}

	function Save($parent_path = "", $post = array())
	{
		if (empty($post))
			$post = $_POST;
	
		//db_connect();
		$datetime = date("Y-m-d H:i:s");
		if ($this->id > 0)
		{
			$query = "UPDATE `objects` SET name='$this->name', class_name='$this->class_name', modified='$datetime' WHERE id = '$this->id'";

			$result = mysql_query($query);
			if (!$result)
			{
				$message = "<b>An error occured while updateing</b><br/>";
				$message .= "<b>Table:</b> objects<br/>";
				$message .= "<b>Query:</b> $query<br/>";
				$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
				$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
				return $message;
			}

			foreach ($this->vars as $var)
			{
				$altname = "v".$var->id;
				$ret = $var->Save(isset($post[$var->id]) ? $post[$var->id] : $post[$altname]);

				if ($ret !== true)
					return $var->name." failed! $ret";
			}
			
			$this->SetById($this->id);
			
			$this->path = GetParentPath($this->GetPath())."/".$this->name;
			
			return true;
		}
		else
		{
			$query = "INSERT INTO `objects` (name, class_name, creator, created, modified) VALUES('$this->name', '$this->class_name', '".$_SESSION['murrix']['user']->id."', '$datetime', '$datetime')";

			$result = mysql_query($query);
			if (!$result)
			{
				$message = "<b>An error occured while inserting</b><br/>";
				$message .= "<b>Table:</b> objects<br/>";
				$message .= "<b>Query:</b> $query<br/>";
				$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
				$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
				return $message;
			}

			$this->id = mysql_insert_id();

			$parent_id = ResolvePath($parent_path);

			$query = "INSERT INTO `relations` (obj2_id, obj1_id, type) VALUES('$this->id', '$parent_id', 'sub')";

			$result = mysql_query($query);
			if (!$result)
			{
				$message = "<b>An error occured while inserting</b><br/>";
				$message .= "<b>Table:</b> relations<br/>";
				$message .= "<b>Query:</b> $query<br/>";
				$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
				$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
				return $message;
			}
			
			$this->InitVars();
			//$this->class = new mClass($this->class_name);
			
			foreach ($this->vars as $var)
			{
				$altname = "v".$var->id;
				$ret = $var->Save(isset($post[$var->id]) ? $post[$var->id] : $post[$altname]);

				if ($ret !== true)
					return $var->name." failed! $ret";
			}
			
			$this->SetById($this->id);
			
			return true;
		}
	}
	
	function Remove()
	{
		$children = mObject::GetRelatedWithRights($this->GetRelatedHash(array(
										array("classes", array("comment"), true),
										array("type", array("sub")),
										array("side", "bottom"))));
	
		foreach ($children as $child)
		{
			$status = $child->Remove();
			if ($status !== true)
				return $status;
		}

		$query = "DELETE FROM `objects` WHERE id = '$this->id'";

		$result = mysql_query($query);
		if (!$result)
		{
			$message = "<b>An error occured while deleting</b><br/>";
			$message .= "<b>Table:</b> objects<br/>";
			$message .= "<b>Query:</b> $query<br/>";
			$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
			$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
			return $message;
		}
		
		$query = "DELETE FROM `relations` WHERE obj1_id = '$this->id' OR obj2_id = '$this->id'";

		$result = mysql_query($query);
		if (!$result)
		{
			$message = "<b>An error occured while deleting</b><br/>";
			$message .= "<b>Table:</b> relations<br/>";
			$message .= "<b>Query:</b> $query<br/>";
			$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
			$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
			return $message;
		}
		
		foreach ($this->vars as $var)
		{
			if (!$var->Remove())
				return $var->name." failed!";
		}
		
		return true;
	}
}

?>