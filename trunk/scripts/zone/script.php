<?

class sZone extends Script
{
	var $zones;
	var $events;
	
	function sZone()
	{
		$this->zone = "";
	}
	
	function EventHandler(&$system, &$response, $event, $args)
	{
		if (is_array($this->events[$event]))
		{
			foreach ($this->events[$event] as $key)
				$response->addAssign($key, "innerHTML", utf8e(compiletpl($this->zones[$key], array())));
		}
	}

	function onActive($arguments)
	{
		foreach ($arguments as $key => $value)
		{
			
			$this->zones[$key] = $value['template'];
			
			if (is_array($value['events']))
			{
				foreach ($value['events'] as $event)
				{
					if (!is_array($this->events[$event]))
						$this->events[$event] = array($key);
					else
						$this->events[$event][] = $key;
				}
			}
			else
			{
				if (!is_array($this->events[$value['events']]))
					$this->events[$value['events']] = array($key);
				else
					$this->events[$value['events']][] = $key;
			}
		}
			
		parent::onActive($arguments);
	}
	
	function Draw(&$system, &$response, $args)
	{
		foreach ($this->zones as $key => $value)
		{
			$zone_args = array();
			$data = compiletplWithOutput($value, $zone_args);
			$response->addAssign($key, "innerHTML", utf8e($data));
			
			if (!empty($zone_args['output']['js']))
				$response->addScript($zone_args['output']['js']);
		}
	}
}
?>