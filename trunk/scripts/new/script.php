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
			
		if (isset($args['action']) && $args['action'] == "save")
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
				$object->setClassName(isset($args['class_name']) ? $args['class_name'] : "folder");
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

		$this->Draw($system, $response, array("class_name" => (isset($args['class_name']) ? $args['class_name'] : "folder"), "path" => $parent->getPath()));
	}
	
	function Draw(&$system, &$response, $args)
	{
		if (isset($args['node_id']))
			$object = new mObject($args['node_id']);
		else if (isset($args['path']))
			$object = new mObject(resolvePath($args['path']));
		else
		{
			$object = new mObject();
			$object->loadByObjectId($args['object_id']);
		}
		
		$newobject = new mObject();
		$newobject->setClassName(isset($args['class_name']) ? $args['class_name'] : "folder");
		$newobject->loadVars();

		ob_start();
		
		include(gettpl("scripts/new", $newobject));

		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
	}
}
?>