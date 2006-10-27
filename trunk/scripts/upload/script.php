<?

class sUpload extends Script
{
	function sUpload()
	{
		$this->zone = "zone_main";
	}
	
	function eventHandler(&$system, $event, $args)
	{
		switch ($event)
		{
			case "newlang":
			case "newlocation":
			case "login":
			case "logout":
			if ($this->active)
				$this->draw($system, $args);
			break;
		}
	}
	
	function execute(&$system, $args)
	{
		if ($args['action'] == "upload")
		{
			global $abspath;
			
			$new_parent = new mObject($args['node_id']);
			
			if (!$new_parent->hasRight("write"))
			{
				$system->addAlert(ucf(i18n("not enough rights")));
				return;
			}
			
			$subitems = GetSubfilesAndSubfolders("$abspath/upload");
			$this->logg = "<hr/>";
			
			foreach ($subitems as $subitem)
			{
				if (is_dir("$abspath/upload/$path/$subitem"))
				{
					$this->importDir($new_parent, "$path/$subitem");
				}
				else
				{
					createObject($new_parent, $subitem, "file", array("file" => "$subitem:$abspath/upload/$path/$subitem"));
					$this->logg .= "Created ".$new_parent->getPathInTree()."/$subitem<br/>";
				}
			}
			
			$system->setZoneData("zone_upload_logg", utf8e($this->logg));
			return;
		}
	
	
		$this->draw($system, $args);
	}
	
	function importDir($parent, $path)
	{
		global $abspath;
		
		$node_id = getNode($parent->getPath()."/".basename($path));
		
		if ($node_id <= 0)
		{
			$node_id = createObject($parent, basename($path), "file_folder");
			$this->logg .= "Created ".$parent->getPathInTree()."/".basename($path)."<br/>";
			
			if ($node_id === false)
				return false;
		}
		
		$new_parent = new mObject($node_id);
		
		$subitems = GetSubfilesAndSubfolders("$abspath/upload/$path");
		
		foreach ($subitems as $subitem)
		{
			if (is_dir("$abspath/upload/$path/$subitem"))
				$this->importDir($new_parent, "$path/$subitem");
			else
			{
				createObject($new_parent, $subitem, "file", array("file" => "$subitem:$abspath/upload/$path/$subitem"));
				$this->logg .= "Created ".$new_parent->getPathInTree()."/$subitem<br/>";
			}
		}
	}
	
	function draw(&$system, $args)
	{
		$node_id = $this->getNodeId($args);

		$data = "";
		if ($node_id > 0)
		{
			$object = new mObject($node_id);
			if ($object->hasRight("create"))
				$data = compiletpl("scripts/upload", array(), $object);
			else
				$data = compiletpl("message", array("title"=>ucf(i18n("error")), "message"=>ucf(i18n("not enough rights"))));
		}
		else
			$data = compiletpl("message", array("title"=>ucf(i18n("error")), "message"=>ucf(i18n("the specified path is invalid"))));

		$system->setZoneData($this->zone, utf8e($data));
	}
}
?>