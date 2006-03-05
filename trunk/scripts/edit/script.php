<?

class sEdit extends Script
{
	function sEdit()
	{
	}
	
	function EventHandler(&$system, &$response, $event, $args)
	{
		switch ($event)
		{
			case "newlang":
			case "newlocation":
			case "login":
			case "logout":
			$this->Draw($system, $response, $args);
			break;
		}
	}

	function Exec(&$system, &$response, $args)
	{
		if (isset($args['action']) && $args['action'] == "save")
		{
			$bError = false;
			if (empty($args['name']))
			{
				$response->addAlert(ucf(i18n("please enter a name")));
				$bError = true;
			}

			if (!(strpos($args['name'], "\\") === false) || !(strpos($args['name'], "/") === false) || !(strpos($args['name'], "+") === false))
			{
				$response->addAlert(ucf(i18n("you can not use '\\', '/' or '+' in the name")));
				$bError = true;
			}

			if (!$bError)
			{
				$node_id = $this->getNodeId($args);
				if ($node_id > 0)
				{
					$object = new mObject($node_id);
	
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
						$response->addScript("OnClickCmd('Exec(\'show\',\'$this->zone\',Hash(\'node_id\',\'$node_id\'))');");
					else
					{
						$message = "Operation unsuccessfull.<br/>";
						$message .= "Error output:<br/>";
						$message .= $object->getLastError();
						$response->addAlert($message);
					}
				}
			}
			return;
		}

		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		if (isset($args['object_id']))
		{
			$object = new mObject();
			$object->loadByObjectId($args['object_id']);
		}
		else
			$object = new mObject($this->getNodeId($args));
		
		ob_start();
		if ($object->getNodeId() > 0)
		{
			if ($object->HasRight("edit"))
				include(gettpl("scripts/edit", $object));
			else
			{
				$titel = ucf(i18n("error"));
				$text = ucf(i18n("not enough rights"));
				include(gettpl("message"));
			}
		}
		else
		{
			$titel = ucf(i18n("error"));
			$text = ucf(i18n("the specified path is invalid"));
			include(gettpl("message"));
		}

		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
	}
}
?>