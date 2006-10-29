<?

class sEdit extends Script
{
	function sEdit()
	{
		$this->zone = "zone_main";
		$this->addActionHandler("save");
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

	function actionSave(&$system, $args)
	{
		$bError = false;
		if (empty($args['name']))
		{
			$system->addAlert(ucf(i18n("please enter a name")));
			$bError = true;
		}

		if (!(strpos($args['name'], "\\") === false) || !(strpos($args['name'], "/") === false) || !(strpos($args['name'], "+") === false))
		{
			$system->addAlert(ucf(i18n("you can not use '\\', '/' or '+' in the name")));
			$bError = true;
		}

		if (!$bError)
		{
			$object = new mObject($this->getNodeId($args));
			if ($object->getNodeId() > 0)
			{
				if ($object->hasRight("write"))
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
							$system->addAlert(utf8e(ucf(str_replace("_", " ", i18n($var->getName(true))))." ".i18n("is a required field")));
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
						$system->addRedirect("exec=show&node_id=".$object->getNodeId());

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
						$system->addAlert($message);
					}
				}
			}
		}
	}
	
	function draw(&$system, $args)
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
			if ($object->hasRight("write"))
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
		
		$system->setZoneData($this->zone, utf8e($data));
		
		if (!empty($javascript))
			$system->addJSScript($javascript);
	}
}
?>