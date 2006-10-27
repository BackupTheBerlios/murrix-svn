<?

class sShow extends Script
{
	function sShow()
	{
		$this->zone = "zone_main";
	}
	
	function eventHandler(&$system, &$response, $event, $args)
	{
		switch ($event)
		{
			case "newlang":
			case "newlocation":
			case "login":
			case "logout":
			if ($this->active)
				$this->draw($system, $response, $args);
			break;
		}
	}

	function execute(&$system, &$response, $args)
	{
		if (!is_array($args))
			$args = array();
	
		// This should be checked and possibly moved to the settingsscript
		if (isset($args['meta']) || isset($args['rebuild_thumb']))
		{
			$node_id = $this->getNodeId($args);
		
			if ($node_id > 0)
			{
				$object = new mObject($node_id);
		
				if ($object->hasRight("write"))
				{
					if (isset($args['meta']))
						$object->setMeta($args['meta'], $args['value']);
		
					// Special case for files
					if (isset($args['rebuild_thumb']))
						delThumbnails($args['rebuild_thumb']);
						
					clearNodeFileCache($object->getNodeId());
				}
			}
		}
		$node_id = $this->getNodeId($args);
		
		if ($node_id > 0)
		{
			$object = new mObject($node_id);
			$_SESSION['murrix']['path'] = $object->getPathInTree();
		}

		$system->triggerEventIntern($response, "newlocation", $args);
	}
	
	function draw(&$system, &$response, $args)
	{
		$node_id = $this->getNodeId($args);

		if ($node_id > 0)
		{
			$object = new mObject($node_id);
			if ($object->hasRight("read"))
				$data = compiletpl("scripts/show/view", array("children_show_page"=>$args['children_show_page']), $object);
			else
				$data = compiletpl("message", array("title"=>ucf(i18n("error")), "message"=>ucf(i18n("not enough rights"))), $object);
		}
		else
			$data = compiletpl("message", array("title"=>ucf(i18n("error")), "message"=>ucf(i18n("the specified path is invalid"))), $object);

		$system->setZoneData($this->zone, utf8e($data));
	}
}
?>