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
		
		$class_name = "";
		if (!empty($args['class_name']))
			$class_name = $args['class_name'];

		if (empty($class_name))
			$class_name = $parent->getMeta("default_class_name", "folder");
		
		if ($parent->hasRight("create") || $parent->hasRight("comment") && $class_name == "comment")
		{
			if (isset($args['action']) && $args['action'] == "save")
			{
				if (!empty($class_name))
				{
					$languages = explode(",", $args['languages']);
					$languages_tosave = array();
				
					foreach ($languages as $language)
					{
						if (!empty($args[$language.'_name']))
						{
							if (!(strpos($args[$language.'_name'], "\\") === false) || !(strpos($args[$language.'_name'], "/") === false) || !(strpos($args[$language.'_name'], "+") === false))
							{
								$system->addAlert(ucf(i18n($language))." ".i18n("version").": ".ucf(i18n("you can not use '\\', '/' or '+' in the name")));
								return;
							}

							$languages_tosave[] = $language;
						}
					}

					if (count($languages_tosave) == 0)
					{
						$system->addAlert(ucf(i18n("nothing to save")));
						return;
					}

					$languages = $languages_tosave;
				
	
					$object = new mObject();
					$object->setClassName($class_name);
					$object->loadVars();
	
					$saved = false;
					foreach ($languages as $language)
					{
						$object->name = trim($args[$language.'_name']);
						$object->icon = trim($args[$language.'_icon']);
						$object->language = $language;
						$object->rights = $parent->getMeta("initial_rights", $parent->getRights());
	
						$vars = $object->getVars();
	
						foreach ($vars as $var)
						{
							$key = $language."_v".$var->id;
							$value = (isset($args[$key]) ? $args[$key] : "");
							
							if (empty($value) && $var->getRequired() && $var->getType() != "boolean")
							{
								$system->addAlert(utf8e(ucf(i18n($language))." ".i18n("version").": ".ucf(str_replace("_", " ", i18n($var->getName(true))))." ".i18n("is a required field")));
								return;
							}
							
							$object->setVarValue($var->name, $value);
						}
	
						if ($object->save())
						{
							guessObjectType($object);
							$saved = true;
						}
						else
						{
							$message = "Operation unsuccessfull.<br/>";
							$message .= "Error output:<br/>";
							$message .= $object->getLastError();
							$system->addAlert($message);
							return;
						}
					}
	
					if ($saved)
					{
						clearNodeFileCache($parent->getNodeId());
						$object->linkWithNode($parent->getNodeId());
						
						$system->addRedirect("exec=show&node_id=".$object->getNodeId());
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
	
		$javascript = "";
		$data = "";
		if ($object->HasRight("create") || $object->HasRight("comment") && $args['class_name'] == "comment")
		{
			$newobject = new mObject();
			$newobject->setClassName(isset($args['class_name']) ? $args['class_name'] : "folder");
			$newobject->loadVars();
			$newobject->loadClassIcon();
			
			$new_args = array("parent_node_id"=>$object->getNodeId());
			$data = compiletplWithOutput("scripts/new", $new_args, $newobject);
			$javascript = $new_args['output']['js'];
		}
		else
			$data = compiletpl("message", array("title"=>ucf(i18n("error")), "message"=>ucf(i18n("not enough rights"))), $object);
			

		$system->setZoneData($this->zone, utf8e($data));
		if (!empty($javascript))
			$system->addScript($javascript);
	}
}
?>