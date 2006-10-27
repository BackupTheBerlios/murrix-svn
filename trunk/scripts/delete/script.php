<?

class sDelete extends Script
{
	function sDelete()
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
					
					$system->addRedirect("exec=show&node_id=$node_id");
					return;
				}
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
			if ($object->hasRight("write"))
				$data = compiletpl("scripts/delete", array(), $object);
			else
				$data = compiletpl("message", array("title"=>ucf(i18n("error")), "message"=>ucf(i18n("not enough rights"))));
		}
		else
			$data = compiletpl("message", array("title"=>ucf(i18n("error")), "message"=>ucf(i18n("the specified path is invalid"))));

		$system->setZoneData($this->zone, utf8e($data));
	}
}
?>