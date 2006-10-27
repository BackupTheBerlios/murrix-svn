<?

class sLangswitch extends Script
{
	function sLangswitch()
	{
		$this->zone = "zone_language";
	}

	function eventHandler(&$system, $event, $args = null)
	{
		switch ($event)
		{
			case "newlang":
			if ($this->active)
				$this->draw($system, $args);
			break;
		}
	}

	function execute(&$system, $args)
	{
		if (isset($args['language']))
		{
			if ($_SESSION['murrix']['language'] != $args['language']);
			{
				$_SESSION['murrix']['language'] = $args['language'];
				unset($_SESSION['murrix']['querycache']);

				$node_id = getNode($_SESSION['murrix']['path']);
				$object = new mObject($node_id);
				$_SESSION['murrix']['path'] = $object->getPath();
	
				//$system->TriggerEventIntern($response, "newlang");
				$system->addJSScript("window.location.reload()");
				return;
			}
		}
		$this->draw($system, $args);
	}

	function draw(&$system, $args)
	{
		$system->setZoneData($this->zone, utf8e(compiletpl("scripts/langswitch", array())));
	}
}

?>