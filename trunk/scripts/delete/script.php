<?

class sDelete extends Script
{
	function sDelete()
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
			$path = $object->getPathInTree();
		}
		else
			$path = $args['path'];
			
		if (isset($args['action']) && $args['action'] == "delete")
		{
			$object = new mObject(resolvePath($path));

			if ($object->hasRight("delete"))
			{
				$_SESSION['murrix']['path'] = GetParentPath($object->getPathInTree());
				$object->deleteNode();
				
				$response->addScript("OnClickCmd('Exec(\'show\',\'$this->zone\',Hash(\'path\',\'".urlencode($_SESSION['murrix']['path'])."\'))');");
				return;
			}
		}

		if (!isset($args['path']))
			$args['path'] = $_SESSION['murrix']['path'];

		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		if (!isset($args['path']))
			$args['path'] = $_SESSION['murrix']['path'];
	
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
			if ($object->HasRight("delete"))
				include(gettpl("scripts/delete", $object));
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