<?

class sTools extends Script
{
	function sTools()
	{
	}
	
	function EventHandler(&$system, &$response, $event, $args = null)
	{
		switch ($event)
		{
			case "newlang":
			case "newlocation":
			case "login":
			case "logout":
			$this->Draw($system, $response, array('path' => $_SESSION['murrix']['path']));
			break;
		}
	}

	function Exec(&$system, &$response, $args)
	{
		if (isset($args['action']))
		{
			$object = new mObject($args['parent_id']);
	
			if ($object->hasRight("edit"))
			{
				if (count($args['node_ids']) == 0)
				{
					$response->addAlert(ucf(i18n("you must select at least one object")));
					return;
				}
	
				$remote_node_id = resolvePath($args['path']);
	
				if ($remote_node_id > 0)
				{
					$remote = new mObject($remote_node_id);
	
					if ($remote->hasRight("edit"))
					{
						switch ($args['action'])
						{
							case "move":
							foreach ($args['node_ids'] as $node_id)
							{
								$child = new mObject($node_id);
			
								$child->linkWithNode($remote_node_id, "sub");
								$child->unlinkWithNode($object->getNodeId(), "sub", "bottom");
							}
							break;
							
							case "link":
							foreach ($args['node_ids'] as $node_id)
							{
								$child = new mObject($node_id);
			
								$child->linkWithNode($remote_node_id, "sub");
							}
							break;
						}
					}
					else
					{
						$response->addAlert(ucf(i18n("you don't have enough rights on the target")));
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
				$response->addAlert(ucf(i18n("you don't have enough rights")));
				return;
			}
		}
		
		$this->Draw($system, $response, array("path" => $_SESSION['murrix']['path']));
	}
	
	function Draw(&$system, &$response, $args)
	{
		$object = new mObject(resolvePath($args['path']));
	
		ob_start();
		
		if ($object->getNodeId() > 0)
		{
			include(gettpl("scripts/tools", $object));

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