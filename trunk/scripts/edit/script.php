<?

class sEdit extends Script
{
	function sEdit()
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
				$object = new mObject(resolvePath($_SESSION['murrix']['path']));

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
					$_SESSION['murrix']['lastcmd'] = "Exec('show', '".$this->zone."', Hash('path', '".$_SESSION['murrix']['path']."'))";
					$system->ExecIntern($response, "show", $this->zone);
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

		if (isset($args['action']) && $args['action'] == "editversion")
		{
			$this->Draw($system, $response, array("object_id" => $args['object_id']));
			return;
		}

		if (isset($args['node_id']))
		{
			$object = new mObject($args['node_id']);
			$_SESSION['murrix']['path'] = $object->getPathInTree();
		}
		else if (isset($args['path']))
			$_SESSION['murrix']['path'] = $args['path'];
		else
		{
			if (empty($_SESSION['murrix']['path']))
			{
				global $site_config;
				$_SESSION['murrix']['path'] = $site_config['sites'][$_SESSION['murrix']['site']]['start'];
			}
		}

		$system->TriggerEventIntern($response, "newlocation");
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