<?

class sVersions extends Script
{
	function sVersions()
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
		if (isset($args['action']) && $args['action'] == "deleteversion")
		{
			$object = new mObject();
			$object->loadByObjectId($args['object_id']);

			if ($object->hasRight("write"))
				$object->deleteCurrentVersion();

			$args['node_id'] = $object->getNodeId();
			clearNodeFileCache($object->getNodeId());
			
			$links = $object->getLinks();
			foreach ($links as $link)
			{
				if ($link['type'] == "sub")
					clearNodeFileCache($link['remote_id']);
			}
		}
		
		$system->triggerEventIntern($response, "newlocation", $args);
	}
	
	function draw(&$system, &$response, $args)
	{
		$node_id = $this->getNodeId($args);

		$data = "";
		if ($node_id > 0)
		{
			$object = new mObject($node_id);
			$data = compiletpl("scripts/versions", array(), $object);
		}
		else
			$data = compiletpl("message", array("titel"=>ucf(i18n("error")), "message"=>ucf(i18n("the specified path is invalid"))));

		$system->setZoneData($this->zone, utf8e($data));
	}
}
?>