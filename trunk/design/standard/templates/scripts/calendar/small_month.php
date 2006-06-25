<?
$first_week_day = date("w", $firstday)-1;
if ($first_week_day == -1) // Sunday
	$first_week_day++;
	
$days_of_month = date("t", $firstday);
	
$last_week_day = date("w", strtotime("+".($days_of_month-1)." days", $firstday));
if ($last_week_day == 0) // Sunday
	$last_week_day = 7;

?>

<fieldset>
	<legend>
		<?=cmd(ucf(i18n(strtolower(date("F", $firstday))))." ".date("Y", $firstday), "exec=calendar&view=month&date=".date("Ymd", $firstday), "link")?>
	</legend>

	<table class="small_calendar_table" cellspacing="1">
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
		<tr class="week_row">
			<?
			for ($n = 0; $n < $days_of_month+(7-$last_week_day)+$first_week_day; $n++)
			{
				$days = $n-$first_week_day;
				if ($days > 0)
					$days = "+$days";
				else
					$days = "-$days";
				
				$time_now = strtotime("$days days", $firstday);
				
				if ($n%7 == 0)
				{
					?></tr><tr class="week_row"><td class="week"><?=cmd(date("W", $time_now), "exec=calendar&view=week&date=".date("Ymd", $time_now), "link")?></td><?
				}
				
				$class = "day";
				$link_class = "link";
				$day_of_week = date("w", $time_now);
				if ($day_of_week == 0 || $day_of_week == 6)
					$link_class .= " red";
					
				if (date("m", $time_now) != date("m", $firstday))
					$link_class .= " gray";
					
				if (date("Y-m-d", $time_now) == date("Y-m-d"))
					$class .= " today";
					
				?><td class="<?=$class?>"><?=cmd(date("j", $time_now), "exec=calendar&view=day&date=".date("Ymd", $time_now), $link_class)?></td><?
			}
		?>
		</tr>
	</table>
</fieldset>