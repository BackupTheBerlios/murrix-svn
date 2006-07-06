<?

class sUpload extends Script
{
	function sUpload()
	{
		$this->zone = "zone_main";
	}
	
	function EventHandler(&$system, &$response, $event, $args)
	{
		switch ($event)
		{
			case "newlang":
			case "newlocation":
			case "login":
			case "logout":
			if ($this->active)
				$this->Draw($system, $response, $args);
			break;
		}
	}
	
	function Exec(&$system, &$response, $args)
	{
		if ($args['action'] == "upload")
		{
			global $abspath;
			
			$new_parent = new mObject($args['node_id']);
			
			if (!$new_parent->hasRight("write"))
			{
				$response->addAlert(ucf(i18n("not enough rights")));
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
			
			$response->addAssign("zone_upload_logg", "innerHTML", utf8e($this->logg));
			return;
		}
	
	
		$this->Draw($system, $response, $args);
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
	
	function Draw(&$system, &$response, $args)
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

		$response->addAssign($this->zone, "innerHTML", utf8e($data));
	}
}
?>