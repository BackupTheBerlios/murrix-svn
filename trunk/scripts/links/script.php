<?

class sLinks extends Script
{
	function sLinks()
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
			if ($args['action'] == "deletelink")
			{
				$object = new mObject($args['node_id']);
	
				if ($object->hasRight("edit"))
					$object->unlinkWithNode($args['remote_id'], $args['type'], $args['direction']);
				else
					$response->addAlert(ucf(i18n("you don't have enough rights to delete this link")));

				$_SESSION['murrix']['path'] = $object->getPathInTree();

				if (!isset($args['path']))
					$args['path'] = $_SESSION['murrix']['path'];
		
				$system->TriggerEventIntern($response, "newlocation", $args);
				return;
			}
			else if ($args['action'] == "newlink")
			{
				$object = new mObject($args['node_id']);
	
				if ($object->hasRight("edit"))
				{
					$remote_node_id = resolvePath($args['path']);

					if ($remote_node_id > 0)
					{
						$remote = new mObject($remote_node_id);

						if ($remote->hasRight("edit"))
							$object->linkWithNode($remote_node_id, $args['type']);
						else
							$response->addAlert(ucf(i18n("you don't have enough rights on the remote object to create this link")));
					}
					else
						$response->addAlert(ucf(i18n("the remote object you specified does not exist")));
				}
				else
					$response->addAlert(ucf(i18n("you don't have enough rights to create a link")));
			
				$this->Draw($system, $response, array("path" => $_SESSION['murrix']['path']));
				return;
			}
		}
		
		if (isset($args['node_id']))
		{
			$object = new mObject($args['node_id']);
			$_SESSION['murrix']['path'] = $object->getPathInTree();
		}
		else if (isset($args['path']))
			$_SESSION['murrix']['path'] = $args['path'];
		else
		{
			if (empty($_SESSION['murrix']['path']))
			{
				global $site_config;
				$_SESSION['murrix']['path'] = $site_config['sites'][$_SESSION['murrix']['site']]['start'];
			}
		}

		$system->TriggerEventIntern($response, "newlocation");
	}
	
	function Draw(&$system, &$response, $args)
	{
		$object = new mObject(resolvePath($args['path']));
	
		ob_start();
		
		if ($object->getNodeId() > 0)
		{
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