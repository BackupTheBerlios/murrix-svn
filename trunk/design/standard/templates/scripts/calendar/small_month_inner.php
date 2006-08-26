<?
$calendar = new mCalendar();

$first_week_day = date("w", $args['firstday'])-1;
if ($first_week_day == -1) // Sunday
	$first_week_day = 6;
	
$days_of_month = date("t", $args['firstday'])-1;
	
$last_week_day = date("w", strtotime("+".($days_of_month)." days", $args['firstday']))-1;
if ($last_week_day == -1) // Sunday
	$last_week_day = 6;

$days_to_show = $days_of_month+(7-$last_week_day)+$first_week_day;

$first_stamp = strtotime("-$first_week_day days", $args['firstday']);
$last_stamp = strtotime("+$days_to_show days", $first_stamp);
$month_events = $calendar->getEvents($args['events'], $first_stamp, $last_stamp-$first_stamp);
?>

<table class="small_calendar_table" cellspacing="0">
	<tr class="title_row">
		<td class="week">&nbsp;</td>
		<td class="monday">M</td>
		<td class="tuesday">T</td>
		<td class="wednesday">W</td>
		<td class="thursday">T</td>
		<td class="friday">F</td>
		<td class="saturday red">S</td>
		<td class="sunday red">S</td>
	</tr>
	<?
	if ($days_to_show > 0)
	{
		for ($n = 0; $n < $days_to_show; $n++)
		{
			$days = $n-$first_week_day;
			
			$time_now = strtotime("$days days", $args['firstday']);
			
			if ($n%7 == 0)
			{
				if ($n > 0)
				{
				?>
					</tr>
				<?
				}
				?>
				<tr class="week_row">
					<td class="week">
						<?=cmd(date("W", $time_now), "exec=calendar&view=week&date=".date("Ymd", $time_now), "link")?>
					</td>
			<?
			}
			
			$class = "day";
			$link_class = "link";
			$day_of_week = date("w", $time_now);
			if ($day_of_week == 0 || $day_of_week == 6)
				$link_class .= " red";
				
			if (date("m", $time_now) != date("m", $args['firstday']))
				$link_class .= " gray";
				
			if (date("Y-m-d", $time_now) == date("Y-m-d"))
				$class .= " today";
			
			$day_events = $calendar->getEvents($month_events, $time_now, 60*60*24);
			
			if (count($day_events) > 0)
				$link_class .= " bold";
			
			?>
			<td class="<?=$class?>">
				<?=cmd(date("j", $time_now), "exec=calendar&view=day&date=".date("Ymd", $time_now), $link_class)?>
			</td>
		<?
		}
		?>
		</tr>
	<?
	}
?>
</table>