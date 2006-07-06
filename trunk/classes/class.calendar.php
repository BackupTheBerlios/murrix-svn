<?

class mCalendar
{
	var $events;
	
	function mCalendar($events = null)
	{
		if ($events == null)
			$this->events = array();
		else
			$this->events = $events;
	}
	
	function getAllEvents()
	{
		$events = fetch("FETCH node WHERE property:class_name='event' NODESORTBY property:version SORTBY var:date,var:time");
		$events = getReadable($events);
		
		for ($n = 0; $n < count($events); $n++)
		{
			if (!isset($events[$n]->rand_color))
				$events[$n]->rand_color = colour('light');
		}
			
		return $events;
	}
	
	function getEvents($events, $start_stamp, $duration)
	{
		$ref_year = array();
		$ref_month = array();
		$ref_day = array();
		$ref_week = array();
	
		$start = $start_stamp;
		while ($start < $start_stamp+$duration)
		{
			$start = strtotime("+1 day", $start);
			
			$year = date("Y", $start);
			if (!in_array($year, $ref_year))
				$ref_year[] = $year;
				
			$month = date("m", $start);
			if (!in_array($month, $ref_month))
				$ref_month[] = $month;
				
			$day = date("d", $start);
			if (!in_array($day, $ref_day))
				$ref_day[] = $day;
			
			$week = date("W", $start);
			if (!in_array($week, $ref_week))
				$ref_week[] = $week;
		}
		
		$list = array();
		foreach ($events as $event)
		{
			$yearly = ($event->getVarValue("reoccuring_yearly", true) == 1);
			$monthly = ($event->getVarValue("reoccuring_monthly", true) == 1);
			$weekly = ($event->getVarValue("reoccuring_weekly", true) == 1);
		
			$str_duration = $event->getVarValue("duration");
				
			$startdate = $event->getVarValue("date");
			
			list($year, $month, $day) = explode("-", $startdate);
			
			$dates = array();
			
			if ($yearly)
			{
				foreach ($ref_year as $ryear)
				{
					if ($monthly)
					{
						foreach ($ref_month as $rmonth)
						{
							$dates[] = "$ryear-$rmonth-$day";
						}
					}
					else
						$dates[] = "$ryear-$month-$day";
				}
			}
			else if ($monthly)
			{
				foreach ($ref_month as $rmonth)
				{
					$dates[] = "$year-$rmonth-$day";
				}
			}
			else
				$dates[] = $startdate;
			
			foreach ($dates as $date)
			{
				$eStart_stamp = strtotime($date);
				
				if (empty($str_duration))
					$eduration = $eStart_stamp;
				else
					$eduration = strtotime($str_duration, $eStart_stamp)-1;
					
				$matched = false;
				while ($eStart_stamp <= $eduration)
				{
					if ($start_stamp <= $eStart_stamp && $eStart_stamp < $start_stamp+$duration)
					{
						$list[] = $event;
						$matched = true;
						break;
					}
					$eStart_stamp = strtotime("+1 day", $eStart_stamp);
				}
				
				if ($matched)
					break;
			}
		}
		
		return $list;
	}

	function getEvents2($startdate, $enddate, $classes = null)
	{
		$class_str = "";
		if ($classes != null)
		{
			foreach ($classes as $class)
			{
				if (!empty($class_str))
					$class_str .= " OR ";

				$class_str .= "property:class_name='$class'";
			}
		}
		else
			$class_str = "property:class_name='event'";

		$events = fetch("FETCH node WHERE ($class_str) NODESORTBY property:version SORTBY var:date");
		$events = getReadable($events);

		$event_list = array();

		$date = $startdate;
		while (true)
		{
			foreach ($events as $child)
			{
				$event_date = getEventDate($child, $date);
				if ($event_date == $date)
				{
					$child->real_date = $date;
					$event_list[] = $child;
				}
			}

			if ($date == $enddate)
				break;

			$date = date("Y-m-d", strtotime("+1 day" ,strtotime($date)));
		}

		usort($event_list, array("Calendar", "RealDateSort"));
		
		return $event_list;
	}

	function RealDateSort($a, $b)
	{
		return date_compare($a->real_date, $b->real_date);
	}

	// Returns an array with the dates for the week
	function getWeek($indate)
	{
		$day_of_week = date("w", strtotime($indate));
		$day_of_week = $day_of_week == 0 ? $day_of_week = 6 : $day_of_week-1;

		if ($day_of_week > 0)
			$first_day_date = date("Y-m-d", strtotime("-$day_of_week days", strtotime($indate)));
		else
			$first_day_date = $indate;
	
		$first_day_timestamp = strtotime($first_day_date);
		
		$week = array($first_day_date);
		for ($n = 1; $n < 7; $n++)
			array_push($week, date("Y-m-d", strtotime("+$n days", $first_day_timestamp)));

		return $week;
	}

	function drawDay($date, $color = "")
	{
		$class = "day";
		if (date("Y-m-d") == $date)
			$class = "today";

		$class = $class.$color;
		?>
		<td class="<?=$class?>">
		<?
			$day_stamp = strtotime($date);
			$day = date("j", $day_stamp);
			if ($day == 1) echo ucf(i18n(strtolower(date("F", strtotime($date)))))." $day";
			else echo $day;
			?>
			<hr/>
			<div class="day">
				<?
				if (count($this->events) > 0)
				{
					foreach ($this->events as $child)
					{
						$event_date = $child->getVarValue("date");
						$date_parts = explode("-", $event_date);

						$show = false;

						if ($date == $event_date)
							$show = true;
							
						else if ($child->getVarValue("reoccuring_yearly", true) == 1 &&
							$date_parts[1]."-".$date_parts[2] == date("m-d", $day_stamp))
							$show = true;
							
						else if ($child->getVarValue("reoccuring_yearly", true) == 1 &&
							$child->getVarValue("reoccuring_monthly", true) == 1 &&
							$date_parts[2] == date("d", $day_stamp))
							$show = true;
							
						else if ($child->getVarValue("reoccuring_monthly", true) == 1 &&
							$date_parts[0]."-".$date_parts[2] == date("Y-d", $day_stamp))
							$show = true;

						if ($show)
						{
							echo "<nobr>";
							echo cmd($child->getName(), "exec=show&node_id=".$child->getNodeId(), "", 	$child->getName());
							echo "</nobr><br/>";
						}
					}
				}
				?>
			</div>
		</td>
	<?
	}
	
	function drawWeek($week)
	{
	?>
		<tr>
			<td class="week">
				<?=date("W", strtotime($week[0]))?>
				<div class="week_text">
					<?=date("Y", strtotime($week[0]))?>
				</div>
			</td>
			<?
			for ($n = 0; $n < count($week); $n++)
				$this->drawDay($week[$n], $n > 4 ? "_red" : "");
			?>
		</tr>
	<?
	}
}
?>
