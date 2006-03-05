<?

class sNew extends Script
{
	function sNew()
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
		$parent_id = $this->getNodeId($args);
		$parent = new mObject($parent_id);
			
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

					$vars = $object->getVars();

					foreach ($vars as $var)
					{
						$key = $language."_v".$var->id;
						$object->setVarValue($var->name, isset($args[$key]) ? $args[$key] : "");
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
					$object->linkWithNode($parent->getNodeId());
					$response->addScript("OnClickCmd('Exec(\'show\',\'$this->zone\',Hash(\'node_id\',\'".$object->getNodeId()."\'))');");
				}

				return;
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

		if ($object->HasRight("create_subnodes", array($newobject->getClassName())))
			include(gettpl("scripts/new", $newobject));
		else
		{
			$titel = ucf(i18n("error"));
			$text = ucf(i18n("not enough rights"));
			include(gettpl("message"));
		}

		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
	}
}
?>