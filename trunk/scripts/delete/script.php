<?

class sDelete extends Script
{
	function sDelete()
	{
		$this->zone = "zone_main";
		$this->addActionHandler("delete");
	}
	
	function eventHandler(&$system, $event, $args)
	{
		switch ($event)
		{
			case "newlang":
			case "newlocation":
			case "login":
			case "logout":
			if ($this->active)
				$this->draw($system, $args);
			break;
		}
	}

	function actionDelete(&$system, $args)
	{
		$object = new mObject($this->getNodeId($args));

		if ($object->getNodeId() > 0)
		{
			if ($object->hasRight("write"))
			{
				$parent_id = getNode(GetParentPath($object->getPathInTree()));
				
				$object->deleteNode();
				
				$system->addRedirect("exec=show&node_id=$parent_id");
			}
			else
				$system->addAlert(ucf(i18n("not enough rights")));
		}
		else
			$system->addAlert(ucf(i18n("the specified path is invalid")));
	}
	
	function draw(&$system, $args)
	{
		$object = new mObject($this->getNodeId($args));

		$data = "";
		if ($object->getNodeId() > 0)
		{
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