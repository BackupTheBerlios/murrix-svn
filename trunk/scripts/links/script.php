<?

class sLinks extends Script
{
	function sLinks()
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
		if (isset($args['action']))
		{
			if ($args['action'] == "deletelink")
			{
				$object = new mObject($args['node_id']);
	
				if ($object->hasRight("edit"))
					$object->unlinkWithNode($args['remote_id'], $args['type'], $args['direction']);
				else
					$response->addAlert(ucf(i18n("you don't have enough rights to delete this link")));

				$_SESSION['murrix']['path'] = $object->getPathInTree();
				$system->TriggerEventIntern($response, "newlocation", $args);
				return;
			}
			else if ($args['action'] == "newlink")
			{
				$object = new mObject($args['node_id']);
	
				if ($object->hasRight("edit"))
				{
					if (isset($args['remote_node_id']))
						$remote_node_id = $args['remote_node_id'];
					else
						$remote_node_id = getNode($args['path']);

					if ($remote_node_id > 0)
					{
						$remote = new mObject($remote_node_id);

						if ($remote->hasRight("edit"))
						{
							if (!$object->linkWithNode($remote_node_id, $args['type']))
							{
								$response->addAlert(ucf(i18n($object->error)));
								return;
							}
						}
						else
						{
							$response->addAlert(ucf(i18n("you don't have enough rights on the remote object to create this link")));
							return;
						}
					}
					else
					{
						$response->addAlert(ucf(i18n("the remote object you specified does not exist")));
						return;
					}
				}
				else
				{
					$response->addAlert(ucf(i18n("you don't have enough rights to create a link")));
					return;
				}
			
				$this->Draw($system, $response, $args);
				return;
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
			include(gettpl("scripts/links", $object));
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