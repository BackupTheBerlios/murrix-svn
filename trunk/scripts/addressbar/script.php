<?

class sAddressbar extends Script
{
	function sAddressbar()
	{
	}

	function EventHandler(&$system, &$response, $event, $args)
	{
		switch ($event)
		{
			case "newlang":
			case "newlocation":
			$this->Draw($system, $response, $args);
			break;
		}
	}

	function Draw(&$system, &$response, $args)
	{
		ob_start();
		$path = $_SESSION['murrix']['path'];
		include(gettpl("scripts/addressbar"));

		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
	}
}

?>