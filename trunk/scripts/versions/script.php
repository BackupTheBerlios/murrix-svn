<?

class sVersions extends Script
{
	function sVersions()
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
			$this->Draw($system, $response, $args);
			break;
		}
	}

	function Exec(&$system, &$response, $args)
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
		
		$system->TriggerEventIntern($response, "newlocation", $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		$node_id = $this->getNodeId($args);

		ob_start();
		if ($node_id > 0)
		{
			$object = new mObject($node_id);
			include(gettpl("scripts/versions", $object));
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