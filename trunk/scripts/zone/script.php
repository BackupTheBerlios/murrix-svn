<?

class sZone extends Script
{
	var $zones;
	
	function sZone()
	{
		$this->zone = "";
	}
	
	function EventHandler(&$system, &$response, $event, $args)
	{
		switch ($event)
		{
			case "newlang":
			case "login":
			case "logout":
			if ($this->active)
				$this->Draw($system, $response, $args);
			break;
		}
	}

	function onActive($arguments)
	{
		foreach ($arguments as $key => $value)
			$this->zones[$key] = $value;
	}
	
	function Draw(&$system, &$response, $args)
	{
		foreach ($this->zones as $key => $value)
			$response->addAssign($key, "innerHTML", utf8e(compiletpl($value, array())));
	}
}
?>