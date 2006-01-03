<?

class sShow extends Script
{
	function sShow()
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
			$this->Draw($system, $response, $args);
			break;
		}
	}

	function Exec(&$system, &$response, $args)
	{
		if (isset($args['node_id']))
		{
			$paths = getPaths($args['node_id']);
			$_SESSION['murrix']['path'] = $paths[0];
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
		
		$object = new mObject(resolvePath($_SESSION['murrix']['path']));

		if ($object->hasRight("edit"))
		{
			if (isset($args['meta']))
				$object->setMeta($args['meta'], $args['value']);

			// Special case for files
			if (isset($args['rebuild_thumb']))
			{
				$thumbnail = new mThumbnail($object->getVarValue("thumbnail_id"));
				$thumbnail->setRebuild();

				$thumbnail = new mThumbnail($object->getVarValue("imagecache_id"));
				$thumbnail->setRebuild();
			}
		}

		if (!isset($args['path']))
			$args['path'] = $_SESSION['murrix']['path'];

		$system->TriggerEventIntern($response, "newlocation", $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		if (!isset($args['path']))
			$args['path'] = $_SESSION['murrix']['path'];
			
		$object = new mObject(resolvePath($args['path']));
	
		ob_start();
		
		if ($object->getNodeId() > 0)
		{
			if ($object->HasRight("read"))
				include(gettpl("scripts/show", $object));
			else
			{
				$titel = ucf(i18n("Error"));
				$text = ucf(i18n("Not enough rights"));
				include(gettpl("message"));
			}
		}
		else
		{
			$titel = ucf(i18n("Error"));
			$text = ucf(i18n("The specified path is invalid"));
			include(gettpl("message"));
		}

		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
	}
}
?>