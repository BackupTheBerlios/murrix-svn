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
		if (isset($args['action']) && $args['action'] == "deletelink")
		{
			$object = new mObject($args['node_id']);

			if ($object->hasRight("edit"))
				$object->unlinkWithNode($args['remote_id'], $args['type']);
		
			$this->Draw($system, $response, array("path" => $_SESSION['murrix']['path']));
			return;
		}
		
		if (isset($args['node_id']))
		{
			$object = new mObject($args['node_id']);
			$_SESSION['murrix']['path'] = $object->getPath();
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