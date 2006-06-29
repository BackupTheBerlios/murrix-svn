<?

class sNew extends Script
{
	function sNew()
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
		$parent_id = $this->getNodeId($args);
		$parent = new mObject($parent_id);
		
		if ($parent->hasRight("create"))
		{
			$class_name = "";
			if (!empty($args['class_name']))
				$class_name = $args['class_name'];
	
			if (empty($class_name))
				$class_name = $parent->getMeta("default_class_name", "folder");
	
			if (isset($args['action']) && $args['action'] == "save")
			{
				if (!empty($class_name))
				{
					if (!empty($args['language']))
						$languages = array($args['language']);
					else
						$languages = $_SESSION['murrix']['languages'];
				
					foreach ($languages as $language)
					{
						if (empty($args[$language.'_name']))
						{
							$response->addAlert(ucf(i18n($language))." ".i18n("version").": ".ucf(i18n("please enter a name")));
							return;
						}
		
						if (!(strpos($args[$language.'_name'], "\\") === false) || !(strpos($args[$language.'_name'], "/") === false) || !(strpos($args[$language.'_name'], "+") === false))
						{
							$response->addAlert(ucf(i18n($language))." ".i18n("version").": ".ucf(i18n("you can not use '\\', '/' or '+' in the name")));
							return;
						}
					}
	
					$object = new mObject();
					$object->setClassName($class_name);
					$object->loadVars();
	
					$saved = false;
					foreach ($languages as $language)
					{
						$object->name = trim($args[$language.'_name']);
						$object->icon = trim($args[$language.'_icon']);
						$object->language = $language;
						$object->rights = $parent->getMeta("initial_rights", "rwcrwc---");
						$object->group_id = $parent->getMeta("initial_group", $parent->getGroupId());
	
						$vars = $object->getVars();
	
						foreach ($vars as $var)
						{
							$key = $language."_v".$var->id;
							$value = (isset($args[$key]) ? $args[$key] : "");
							
							if (empty($value) && $var->getRequired() && $var->getType() != "boolean")
							{
								$response->addAlert(utf8e(ucf(i18n($language))." ".i18n("version").": ".ucf(str_replace("_", " ", i18n($var->getName(true))))." ".i18n("is a required field")));
								return;
							}
							
							$object->setVarValue($var->name, $value);
						}
	
						if ($object->save())
							$saved = true;
						else
						{
							$message = "Operation unsuccessfull.<br/>";
							$message .= "Error output:<br/>";
							$message .= $object->getLastError();
							$response->addAlert($message);
							return;
						}
					}
	
					if ($saved)
					{
						clearNodeFileCache($parent->getNodeId());
						$object->linkWithNode($parent->getNodeId());
						$response->addScript("setHash('exec=show&node_id=".$object->getNodeId()."');");
					}
	
					return;
				}
			}
		}
		
		$args['class_name'] = $class_name;
		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		$parent_id = $this->getNodeId($args);
		$object = new mObject($parent_id);
	
		$newobject = new mObject();
		$newobject->setClassName(isset($args['class_name']) ? $args['class_name'] : "folder");
		$newobject->loadVars();

		ob_start();

		$javascript = "";

		if ($object->HasRight("create"))
			include(gettpl("scripts/new", $newobject));
		else
		{
			$titel = ucf(i18n("error"));
			$text = ucf(i18n("not enough rights"));
			include(gettpl("message"));
		}

		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
		$response->addScript($javascript);
	}
}
?>