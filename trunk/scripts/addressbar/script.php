<?

class sAddressbar extends Script
{
	var $divider;

	function sAddressbar()
	{
		$this->zone = "zone_addressbar";
		$this->divider = "/";
	}

	function onActive($arguments)
	{
		if (!empty($arguments['divider']))
			$this->divider = $arguments['divider'];
			
		parent::onActive($arguments);
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
		$response->addAssign($this->zone, "innerHTML", utf8e(compiletpl("scripts/addressbar", array("divider"=>$this->divider, "path"=>$_SESSION['murrix']['path']))));
	}
}

?>