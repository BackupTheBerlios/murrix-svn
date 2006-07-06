<?

class sCalendar extends Script
{
	var $calendars;
	var $view;
	var $date;
	
	function sCalendar()
	{
		$this->zone = "zone_main";
		$this->date = date("Ymd");
		$this->view = "month";
		$this->fillCalendars();
	}
	
	function EventHandler(&$system, &$response, $event, $args = null)
	{
		switch ($event)
		{
			case "newlang":
			case "newlocation":
			if ($this->active)
				$this->Draw($system, $response, array());
			break;
			
			case "login":
			case "logout":
			$this->fillCalendars();
			if ($this->active)
				$this->Draw($system, $response, array());
			break;
		}
	}
	
	function fillCalendars()
	{
		$this->calendars = array();
		$home_id = $_SESSION['murrix']['user']->home_id;
		if ($home_id > 0)
		{
			$home = new mObject($home_id);
			$calendar_id = getNode($home->getPath()."/calendar", "eng");
			if ($calendar_id > 0)
			{
				$name = $home->getName();
				$count = 0;
				while (isset($this->calendars[$name]))
				{
					$count++;
					$name .= $count;
				}
				
				$children = fetch("FETCH node WHERE link:node_top='$calendar_id' AND link:type='sub' AND property:class_name='folder' NODESORTBY property:version SORTBY property:name");
				
				for ($n = 0; $n < count($children); $n++)
				{
					$children[$n]->color = colour('light');
					$children[$n]->active = true;
				}
				
				$this->calendars[$name] = $children;
			}
		}
			
		$groups = $_SESSION['murrix']['user']->getGroups();
		foreach ($groups as $groupname)
		{
			$group = new mGroup();
			$group->setByName($groupname);
			
			$home_id = $group->home_id;
			
			if ($home_id > 0)
			{
				$home = new mObject($home_id);
				$calendar_id = getNode($home->getPath()."/calendar", "eng");
				if ($calendar_id > 0)
				{
					$name = $home->getName();
					$count = 0;
					while (isset($this->calendars[$name]))
					{
						$count++;
						$name .= $count;
					}
					
					$children = fetch("FETCH node WHERE link:node_top='$calendar_id' AND link:type='sub' AND property:class_name='folder' NODESORTBY property:version SORTBY property:name");
				
					for ($n = 0; $n < count($children); $n++)
					{
						$children[$n]->color = colour('light');
						$children[$n]->active = true;
					}
				
					$this->calendars[$name] = $children;
				}
			}
		}
	}
	
	function getEvents()
	{
		$events = array();
		$node_ids = array();
		foreach ($this->calendars as $name => $list)
		{
			for ($i = 0; $i < count($this->calendars[$name]); $i++)
			{
				if (!$this->calendars[$name][$i]->active)
					continue;
					
				$children = fetch("FETCH node WHERE link:node_top='".$this->calendars[$name][$i]->getNodeId()."' AND link:type='sub' AND property:class_name='event' NODESORTBY property:version SORTBY var:date");
				
				$children_unique = array();
				
				for ($n = 0; $n < count($children); $n++)
				{
					if (!in_array($children[$n]->getNodeId(), $node_ids))
					{
						$children[$n]->rand_color = $this->calendars[$name][$i]->color;
						$children_unique[] = $children[$n];
						$node_ids[] = $children[$n]->getNodeId();
					}
				}
				
				$events = array_merge($events, $children_unique);
			}
		}
		
		return $events;
	}

	function Exec(&$system, &$response, $args)
	{
		if (!empty($args['view']))
			$this->view = $args['view'];
		
		if (!empty($args['date']))
			$this->date = $args['date'];
	
		if (!empty($args['toggle']))
		{
			foreach ($this->calendars as $name => $list)
			{
				for ($i = 0; $i < count($this->calendars[$name]); $i++)
				{
					if ($this->calendars[$name][$i]->getNodeId() == $args['toggle'])
					{
						$this->calendars[$name][$i]->active = !$this->calendars[$name][$i]->active;
						break;
					}
				}
			}
			
			$events = $this->getEvents();
			$data = "";
			switch ($this->view)
			{
				case "month":
				$data = compiletpl("scripts/calendar/month_view", array("date"=>$this->date, "calendars"=>$this->calendars, "view"=>$this->view, "events"=>$events, "firstday"=>strtotime(date("Y-m", strtotime($this->date))."-01")));
				break;
				
				case "week":
				$data = compiletpl("scripts/calendar/week_view", array("date"=>$this->date, "calendars"=>$this->calendars, "view"=>$this->view, "events"=>$events, "firstday"=>strtotime($this->date)));
				break;
				
				case "day":
				$data = compiletpl("scripts/calendar/day_view", array("date"=>$this->date, "calendars"=>$this->calendars, "view"=>$this->view, "events"=>$events, "firstday"=>strtotime($this->date)));
				break;
			}

		
			$response->addAssign("calendar_main_zone", "innerHTML", utf8e($data));
			return;
		}
	
	
		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		$events = $this->getEvents();

		$data = compiletpl("scripts/calendar/view", array("date"=>$this->date, "calendars"=>$this->calendars, "view"=>$this->view, "events"=>$events,"firstday"=>strtotime($this->date)));

		$response->addAssign($this->zone, "innerHTML", utf8e($data));
	}
}
?>