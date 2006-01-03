<?

class sNew extends Script
{
	function sNew()
	{
	}
	
	function EventHandler(&$system, &$response, $event, $args = null)
	{
		switch ($event)
		{
			case "newlang":
			case "newlocation":
			case "login":
			case "logout":
			$this->Draw($system, $response, array('path' => $_SESSION['murrix']['path']));
			break;
		}
	}

	function Exec(&$system, &$response, $args)
	{
		if (isset($args['node_id']))
			$parent = new mObject($args['node_id']);
		else if (isset($args['path']))
			$parent = new mObject(resolvePath($args['path']));
		else
			$parent = new mObject(resolvePath($_SESSION['murrix']['path']));
			
		$class_name = "";
		if (!empty($args['class_name']))
		{
			if ($parent->HasRight("create_subnodes", array($args['class_name'])))
				$class_name = $args['class_name'];
		}

		if (empty($class_name))
		{
			if ($parent->HasRight("create_subnodes", array("folder")))
				$class_name = "folder";
		}

		if (empty($class_name))
		{
			$classes = getClassList();

			foreach ($classes as $class)
			{
				if ($parent->HasRight("create_subnodes", array($class)))
				{
					$class_name = $class;
					break;
				}
			}
		}
			
		if (isset($args['action']) && $args['action'] == "save")
		{
			if (!empty($class_name))
			{
				$bError = false;
				if (empty($args['name']))
				{
					$response->addAlert(ucf(i18n("please enter a name")));
					$bError = true;
				}
	
				if (!$bError)
				{
					$object = new mObject();
					$object->setClassName($class_name);
					$object->loadVars();
	
					$object->name = trim($args['name']);
					$object->icon = trim($args['icon']);
					$object->language = trim($args['language']);
	
					$vars = $object->getVars();
	
					foreach ($vars as $var)
					{
						$key = "v".$var->id;
						$object->setVarValue($var->name, isset($args[$key]) ? $args[$key] : (isset($args[$var->id]) ? $args[$var->id] : ""));
					}
	
					if ($object->save())
					{
						$object->linkWithNode($parent->getNodeId());
	
						$_SESSION['murrix']['lastcmd'] = "Exec('show', '".$this->zone."', Hash('node_id', '".$object->getNodeId()."'))";
						$system->ExecIntern($response, "show", $this->zone, array("node_id" => $object->getNodeId()));
					}
					else
					{
						$message = "Operation unsuccessfull.<br/>";
						$message .= "Error output:<br/>";
						$message .= $object->getLastError();
						$response->addAlert($message);
					}
				}
				return;
			}
		}

		$this->Draw($system, $response, array("class_name" => $class_name, "path" => $parent->getPath()));
	}
	
	function Draw(&$system, &$response, $args)
	{
		if (isset($args['node_id']))
			$object = new mObject($args['node_id']);
		else if (isset($args['path']))
			$object = new mObject(resolvePath($args['path']));
		else
		{
			$object = new mObject($_SESSION['murrix']['path']);
		}
		
		$newobject = new mObject();
		$newobject->setClassName(isset($args['class_name']) ? $args['class_name'] : "folder");
		$newobject->loadVars();

		ob_start();

		if ($object->HasRight("create_subnodes", array($newobject->getClassName())))
			include(gettpl("scripts/new", $newobject));
		else
		{
			$titel = ucf(i18n("Error"));
			$text = ucf(i18n("Not enough rights"));
			include(gettpl("message"));
		}

		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
	}
}
?>