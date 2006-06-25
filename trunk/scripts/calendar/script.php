<?

class sCalendar extends Script
{
	function sCalendar()
	{
		$this->zone = "zone_main";
	}
	
	function EventHandler(&$system, &$response, $event, $args = null)
	{
		switch ($event)
		{
			case "newlang":
			case "newlocation":
			case "login":
			case "logout":
			$this->Draw($system, $response, array());
			break;
		}
	}

	function Exec(&$system, &$response, $args)
	{
		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		if (!isset($args['date']))
			$date = date("Y-m-d", strtotime("now"));
		else
			$date = $args['date'];

		$children = fetch("FETCH node WHERE property:class_name='event' AND !var:calendar_hide='1' NODESORTBY property:version SORTBY property:name");
	
		$events = getReadable($children);

		$view = empty($args['view']) ? "month" : $args['view'];
		$date = empty($args['date']) ? date("Ymd") : $args['date'];

		ob_start();
		
		include(gettpl("scripts/calendar"));

		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
	}
}
?>