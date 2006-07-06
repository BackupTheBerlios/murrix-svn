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
		
		$javascript = "";
		$data = "";
		if ($object->getNodeId() > 0)
		{
			if ($object->HasRight("write"))
			{
				$edit_args = array();
				$data = compiletplWithOutput("scripts/edit", $edit_args, $object);
				$javascript = $edit_args['output']['js'];
			}
			else
				$data = compiletpl("message", array("title"=>ucf(i18n("error")), "message"=>ucf(i18n("not enough rights"))));
		}
		else
			$data = compiletpl("message", array("title"=>ucf(i18n("error")), "message"=>ucf(i18n("the specified path is invalid"))));
		
		$response->addAssign($this->zone, "innerHTML", utf8e($data));
		if (!empty($javascript))
			$response->addScript($javascript);
	}
}
?>