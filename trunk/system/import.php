<?

function createObject($parent, $name, $class = "folder", $values = null)
{
	$object = new mObject();
	$object->setClassName($class);
	$object->loadVars();

	$object->name = $name;
	$object->language = $_SESSION['murrix']['language'];
	$object->rights = $parent->getMeta("initial_rights", $parent->getRights());
	
	if (is_array($values))
	{
		foreach ($values as $key => $value)
			$object->setVarValue($key, $value);
	}

	if ($object->save())
	{
		guessObjectType($object);
		$object->linkWithNode($parent->getNodeId());
		clearNodeFileCache($parent->getNodeId());
		return $object->getNodeId();
	}
	
	return false;
}

function importDir($parent, $path)
{
	global $abspath;
	$logtext = "";
	
	$node_id = getNode($parent->getPath()."/".basename($path));
	
	if ($node_id <= 0)
	{
		$node_id = createObject($parent, basename($path), "file_folder");
		$logtext .= "Created ".$parent->getPathInTree()."/".basename($path)."<br/>";
		
		if ($node_id === false)
			return false;
	}
	
	$new_parent = new mObject($node_id);
	
	$subitems = GetSubfilesAndSubfolders($path);
	
	foreach ($subitems as $subitem)
	{
		if (is_dir("$path/$subitem"))
			$logtext .= importDir($new_parent, "$path/$subitem");
		else
		{
			createObject($new_parent, $subitem, "file", array("file" => "$subitem:$path/$subitem"));
			$logtext .= "Created ".$new_parent->getPathInTree()."/$subitem<br/>";
		}
	}
	
	return $logtext;
}

function importLines($lines, $fields, $class_name, $parent)
{
	$count = 0;
	foreach ($lines as $line)
	{
		$line = trim($line);
	
		if (empty($line))
			continue;
	
		$array = explode(";", $line);
		
		$newobject = new mObject();
		$newobject->setClassName($class_name);
		$newobject->loadVars();

		$newobject->name = trim($array[$fields['name']]);
		$newobject->language = $_SESSION['murrix']['language'];
		$newobject->rights = $parent->getMeta("initial_rights", $parent->getRights());

		$vars = $newobject->getVars();

		foreach ($vars as $var)
		{
			if (!isset($fields[$var->name]))
				continue;
				
			$value = $array[$fields[$var->name]];
			$newobject->setVarValue($var->name, $value);
		}

		if ($newobject->save())
		{
			guessObjectType($newobject);
			clearNodeFileCache($parent->getNodeId());
			$newobject->linkWithNode($parent->getNodeId());
			$count++;
		}
		else
		{
			return mMsg::add("importLines", $object->getLastError(), true);
		}
	}
	
	return $count;
}

?>