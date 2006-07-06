<?

class sAddressbar extends Script
{
	function sAddressbar()
	{
		$this->zone = "zone_addressbar";
	}

	function EventHandler(&$system, &$response, $event, $args)
	{
		switch ($event)
		{
			case "newlang":
			case "newlocation":
			if ($this->active)
				$this->Draw($system, $response, $args);
			break;
		}
	}

	function Draw(&$system, &$response, $args)
	{
		$response->addAssign($this->zone, "innerHTML", utf8e(compiletpl("scripts/addressbar", array("path"=>$_SESSION['murrix']['path']))));
	}
}

?>