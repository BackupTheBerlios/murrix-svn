<?

class sUpload extends Script
{
	function sUpload()
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
		if (isset($args['node_id']))
		{
			$object = new mObject($args['node_id']);
			$path = $object->getPath();
		}
		else if (isset($args['path']))
			$path = $args['path'];
		else
		{
			if (empty($_SESSION['murrix']['path']))
			{
				global $site_config;
				$path = $site_config['sites'][$_SESSION['murrix']['site']]['start'];
			}
		}
		
		$this->Draw($system, $response, array("path" => $path));

	}
	
	function Draw(&$system, &$response, $args)
	{
		if (isset($args['node_id']))
			$object = new mObject($args['node_id']);
		else if (isset($args['path']))
			$object = new mObject(resolvePath($args['path']));
		else
		{
			$object = new mObject();
			$object->loadByObjectId($args['object_id']);
		}
	
		ob_start();
		
		if ($object->getNodeId() > 0)
		{
			if ($object->HasRight("create_subnodes"))
				include(gettpl("scripts/upload", $object));
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