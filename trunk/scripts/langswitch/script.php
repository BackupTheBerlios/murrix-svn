<?

class sLangswitch extends Script
{
	function sLangswitch()
	{
		$this->zone = "zone_language";
	}

	function EventHandler(&$system, &$response, $event, $args = null)
	{
		switch ($event)
		{
			case "newlang":
			if ($this->active)
				$this->Draw($system, $response, $args);
			break;
		}
	}

	function Exec(&$system, &$response, $args)
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
				$response->addScript("window.location.reload()");
				return;
			}
		}
		$this->Draw($system, $response, $args);
	}

	function Draw(&$system, &$response, $args)
	{
		$response->addAssign($this->zone, "innerHTML", utf8e(compiltetpl("scripts/langswitch", array())));
	}
}

?>