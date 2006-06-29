<?

class sEdit extends Script
{
	function sEdit()
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
	
					if ($object->HasRight("write"))
					{
						$object->name = trim($args['name']);
						$object->icon = trim($args['icon']);
						
						$newlang = ($object->getLanguage() != trim($args['language']));
						$object->language = trim($args['language']);
		
						$vars = $object->getVars();
		
						foreach ($vars as $var)
						{
							$key = "v".$var->id;
							
							$value = (isset($args[$key]) ? $args[$key] : (isset($args[$var->id]) ? $args[$var->id] : ""));
							
							if (empty($value) && $var->getRequired() && $var->getType() != "boolean")
							{
								$response->addAlert(utf8e(ucf(str_replace("_", " ", i18n($var->getName(true))))." ".i18n("is a required field")));
								return;
							}
							
							$object->setVarValue($var->name, $value);
						}
						
						if ($args['newversion'] == "on" || $newlang)
							$ret = $object->save();
						else
							$ret = $object->saveCurrent();
							
						if ($ret)
						{
							$response->addScript("setHash('exec=show&node_id=$node_id');");
							clearNodeFileCache($object->getNodeId());
							
							$links = $object->getLinks();
							foreach ($links as $link)
							{
								if ($link['type'] == "sub")
									clearNodeFileCache($link['remote_id']);
							}
						}
						else
						{
							$message = "Operation unsuccessfull.<br/>";
							$message .= "Error output:<br/>";
							$message .= $object->getLastError();
							$response->addAlert($message);
						}
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
		
		$javascript = "";
		
		if ($object->getNodeId() > 0)
		{
			if ($object->HasRight("write"))
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
		$response->addScript($javascript);
	}
}
?>