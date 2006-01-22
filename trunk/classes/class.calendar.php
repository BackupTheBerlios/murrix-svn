<?

class Calendar
{
	var $events;
	
	function Calendar($events = null)
	{
		if ($events == null)
			$this->events = array();
		else
			$this->events = $events;
	}

	function getEvents($startdate, $enddate, $classes = null)
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

		$events = fetch("FETCH node WHERE $class_str NODESORTBY property:version SORTBY var:date");
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
							echo cmd($child->getName(), "Exec('show', 'zone_main', Hash('path', '".$child->getPathInTree()."'))", "", 	$child->getName());
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
