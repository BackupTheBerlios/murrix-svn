<?

class sLinks extends Script
{
	function sLinks()
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
		if (isset($args['action']))
		{
			if ($args['action'] == "deletelink")
			{
				$object = new mObject($args['node_id']);
	
				/*$links = $object->getLinks();
				if ($object->getNumLinksSubBottom() <= 1 && ($link['type'] == "sub" && $link['direction'] == "bottom"))
					$response->addAlert(ucf(i18n("unable to delete last link")));
				else*/ 
				if ($object->hasRight("write"))
				{
					$object->unlinkWithNode($args['remote_id'], $args['type'], $args['direction']);
					clearNodeFileCache($object->getNodeId());
					clearNodeFileCache($args['remote_id']);

					$_SESSION['murrix']['path'] = $object->getPathInTree();
					$system->TriggerEventIntern($response, "newlocation", $args);
				}
				else
					$system->addAlert(ucf(i18n("you don't have enough rights to delete this link")));
					
				return;
			}
			else if ($args['action'] == "newlink")
			{
				$object = new mObject($args['node_id']);
	
				if ($object->hasRight("write"))
				{
					if (isset($args['remote_node_id']))
						$remote_node_id = $args['remote_node_id'];
					else
						$remote_node_id = getNode($args['path']);

					if ($remote_node_id > 0)
					{
						$remote = new mObject($remote_node_id);

						if ($remote->hasRight("write"))
						{
							if (!$object->linkWithNode($remote_node_id, $args['type']))
							{
								$system->addAlert(ucf(i18n($object->error)));
								return;
							}
							clearNodeFileCache($object->getNodeId());
							clearNodeFileCache($remote_node_id);
						}
						else
						{
							$system->addAlert(ucf(i18n("you don't have enough rights on the remote object to create this link")));
							return;
						}
					}
					else
					{
						$system->addAlert(ucf(i18n("the remote object you specified does not exist")));
						return;
					}
				}
				else
				{
					$system->addAlert(ucf(i18n("you don't have enough rights to create a link")));
					return;
				}
				
				$args['message'] = ucf(i18n("created new link successfully"));
				$_SESSION['murrix']['path'] = $object->getPathInTree();
				$system->triggerEventIntern($response, "newlocation", $args);
				return;
			}
		}
		
		$this->draw($system, $response, $args);
	}
	
	function draw(&$system, &$response, $args)
	{
		$node_id = $this->getNodeId($args);

		$data = "";
		if ($node_id > 0)
		{
			$object = new mObject($node_id);
			$data = compiletpl("scripts/links", array("message"=>$args['message']), $object);
		}
		else
			$data = compiletpl("message", array("title"=>ucf(i18n("error")), "message"=>ucf(i18n("the specified path is invalid"))));

		$system->setZoneData($this->zone, utf8e($data));
	}
}
?>