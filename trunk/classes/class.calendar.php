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
				<nobr>
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
							echo cmd($child->getName(), "Exec('show', 'zone_main', Hash('path', '".$child->getPathInTree()."'))", "", 	$child->getName());
							echo "<br/>";
						}
					}
				}
				?>
				</nobr>
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
