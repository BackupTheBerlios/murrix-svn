<?

class sSettings extends Script
{
	function sSettings()
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
		if ($args['action'] == "meta")
		{
			$node_id = $this->getNodeId($args);
		
			if ($node_id > 0)
			{
				$object = new mObject($node_id);
		
				if ($object->hasRight("write"))
				{
					$object->setMeta("children_show_num_per_page", $args['children_show_num_per_page']);
					
					$object->setMeta("show_comments", $args['show_comments'] == "on" ? 1 : "");
					
					$object->setMeta("comment_show_num_per_page", $args['comment_show_num_per_page']);
					
					$object->setMeta("show_versionstab", $args['show_versionstab'] == "on" ? 1 : "");
					
					$object->setMeta("show_linkstab", $args['show_linkstab'] == "on" ? 1 : "");
					
					$object->setMeta("view", $args['view']);
					
					$object->setMeta("default_class_name", $args['default_class_name']);
					
					if ($args['sort_by'] == "custom")
						$object->setMeta("sort_by", $args['sort_by_custom']);
					else
						$object->setMeta("sort_by", $args['sort_by']);
					
					$object->setMeta("sort_direction", $args['sort_direction']);
					
					$object->setMeta("initial_rights", $args['initial_rights']);
					
					clearNodeFileCache($object->getNodeId());
					delObjectFromCache($object->getId());
				}
			}
		}
		
		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		$node_id = $this->getNodeId($args);

		$data = "";
		if ($node_id > 0)
		{
			$object = new mObject($node_id);
			if ($object->HasRight("write"))
				$data = compiletpl("scripts/settings", array(), $object);
			else
				$data = compiletpl("message", array("title"=>ucf(i18n("error")), "message"=>ucf(i18n("not enough rights"))));
		}
		else
			$data = compiletpl("message", array("title"=>ucf(i18n("error")), "message"=>ucf(i18n("the specified path is invalid"))));

		$response->addAssign($this->zone, "innerHTML", utf8e($data));
	}
}
?>