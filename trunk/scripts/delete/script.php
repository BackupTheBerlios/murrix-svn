<?

class sDelete extends Script
{
	function sDelete()
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
		if (isset($args['action']) && $args['action'] == "delete")
		{
			$node_id = $this->getNodeId($args);

			if ($node_id > 0)
			{
				$object = new mObject($node_id);
	
				if ($object->hasRight("write"))
				{
					$_SESSION['murrix']['path'] = GetParentPath($object->getPathInTree());
					$node_id = getNode($_SESSION['murrix']['path']);
					clearNodeFileCache($object->getNodeId());
					$object->deleteNode();

					$response->addScript("OnClickCmd('Exec(\'show\',\'$this->zone\',Hash(\'node_id\',\'$node_id\'))');");
					return;
				}
			}
		}

		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		$node_id = $this->getNodeId($args);

		ob_start();
		if ($node_id > 0)
		{
			$object = new mObject($node_id);
			if ($object->HasRight("write"))
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