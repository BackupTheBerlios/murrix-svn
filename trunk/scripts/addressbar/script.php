<?

class sAddressbar extends Script
{
	function sAddressbar()
	{
	}

	function EventHandler(&$system, &$response, $event, $args = null)
	{
		switch ($event)
		{
			case "newlang":
			case "newlocation":
			$this->Draw($system, $response, array('path' => $_SESSION['murrix']['path']));
			break;
		}
	}

	function Draw(&$system, &$response, $args)
	{
		ob_start();
		$path = $args['path'];

		$object = new mObject(resolvePath($path));

		$paths = $object->getValidPaths("read");

		if (count($paths) > 0)
		{
			if (!in_array($path, $paths))
				$path = $paths[0];
		}

		include(gettpl("scripts/addressbar"));

		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
	}
}

?>