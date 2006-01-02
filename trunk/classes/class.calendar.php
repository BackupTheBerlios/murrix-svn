<?

class Calendar
{
	function Calendar()
	{
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
			$day = date("j", strtotime($date));
			if ($day == 1) echo ucf(i18n(strtolower(date("F", strtotime($date)))))." $day";
			else echo $day;
			?>
			<hr/>
			<div class="day">
				<nobr>
				<?
				$node_id = resolvePath("/Root/Etek/Hidden/Events", "eng");

				$day = date("j", strtotime($date));
				$month = date("n", strtotime($date));
				$year = date("Y", strtotime($date));

				$children = fetch("FETCH node WHERE link:node_top='$node_id' AND link:type='sub' AND property:class_name='event' AND var:day='$day' AND (var:month='$month' OR var:month='-1') AND (var:year='$year' OR var:year='-1') NODESORTBY !property:version SORTBY property:name");


				foreach ($children as $child)
				{
					echo cmd($child->getName(), "Exec('show', 'zone_main', Hash('path', '".$child->getPath()."'))", "", $child->getName());
					echo "<br/>";
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
