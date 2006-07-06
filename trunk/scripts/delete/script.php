<?

class sDelete extends Script
{
	function sDelete()
	{
		$this->zone = "zone_main";
	}
	
	function EventHandler(&$system, &$response, $event, $args)
	{
		switch ($event)
		{
			case "newlang":
			case "newlocation":
			case "login":
			case "logout":
			if ($this->active)
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

					$response->addScript("setHash('exec=show&node_id=$node_id');");
					return;
				}
			}
		}

		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		$node_id = $this->getNodeId($args);

		$data = "";
		if ($node_id > 0)
		{
			$object = new mObject($node_id);
			if ($object->HasRight("write"))
				$data = compiletpl("scripts/delete", array(), $object);
			else
				$data = compiletpl("message", array("title"=>ucf(i18n("error")), "message"=>ucf(i18n("not enough rights"))));
		}
		else
			$data = compiletpl("message", array("title"=>ucf(i18n("error")), "message"=>ucf(i18n("the specified path is invalid"))));

		$response->addAssign($this->zone, "innerHTML", utf8e($data));
	}
}
?>