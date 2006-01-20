<?

class sLangswitch extends Script
{
	function sLangswitch()
	{
	}

	function EventHandler(&$system, &$response, $event, $args = null)
	{
		switch ($event)
		{
			case "newlang":
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

				$node_id = resolvePath($_SESSION['murrix']['path']);
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
		ob_start();
		include(gettpl("scripts/langswitch"));

		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
	}
}

?>