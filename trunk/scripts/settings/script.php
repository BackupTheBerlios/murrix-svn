<?

class sSettings extends Script
{
	function sSettings()
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
		if ($args['action'] == "meta")
		{
			$node_id = $this->getNodeId($args);
		
			if ($node_id > 0)
			{
				$object = new mObject($node_id);
		
				if ($object->hasRight("write"))
				{
					$object->setMeta("children_show_num_per_page", $args['children_show_num_per_page']);
					
					$object->setMeta("hide_comments", $args['hide_comments'] == "on" ? 1 : "");
					
					$object->setMeta("comment_show_num_per_page", $args['comment_show_num_per_page']);
					
					$object->setMeta("hide_versionstab", $args['hide_versionstab'] == "on" ? 1 : "");
					
					$object->setMeta("hide_linkstab", $args['hide_linkstab'] == "on" ? 1 : "");
					
					$object->setMeta("view", $args['view']);
					
					$object->setMeta("default_class_name", $args['default_class_name']);
					
					if ($args['sort_by'] == "custom")
						$object->setMeta("sort_by", $args['sort_by_custom']);
					else
						$object->setMeta("sort_by", $args['sort_by']);
					
					$object->setMeta("sort_direction", $args['sort_direction']);
					
					$object->setMeta("initial_rights", $args['initial_rights']);
					
					$object->setMeta("initial_group", $args['initial_group']);
				}
			}
		}
		
		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		$node_id = $this->getNodeId($args);

		ob_start();
		if ($node_id > 0)
		{
			$object = new mObject($node_id);
			if ($object->HasRight("write"))
				include(gettpl("scripts/settings", $object));
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