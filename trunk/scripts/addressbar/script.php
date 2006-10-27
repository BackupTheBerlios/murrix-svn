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

	function eventHandler(&$system, $event, $args)
	{
		switch ($event)
		{
			case "newlang":
			case "newlocation":
			if ($this->active)
				$this->draw($system, $args);
			break;
		}
	}

	function draw(&$system, $args)
	{
		$system->setZoneData($this->zone, utf8e(compiletpl("scripts/addressbar", array("divider"=>$this->divider, "path"=>$_SESSION['murrix']['path']))));
	}
}

?>