<?

$last_stamp = strtotime("+1 week", $firstday);
$week_events = $calendar->getEvents($events, $firstday, $last_stamp-$firstday);
?>

<fieldset>
	<legend>
		<?=cmd(ucf(i18n("week"))." ".date("W", $firstday)." ".date("Y", $firstday), "exec=calendar&view=week&date=".date("Ymd", $firstday), "link")?>
	</legend>

	<table class="big_week_table" cellspacing="1">
		<tr class="title_row">
			<td class="time">&nbsp;</td>
			<td class="monday">Monday<br/><?=date("Y-m-d", $firstday)?></td>
			<td class="tuesday">Tuesday<br/><?=date("Y-m-d", strtotime("+1 days", $firstday))?></td>
			<td class="wednesday">Wednesday<br/><?=date("Y-m-d", strtotime("+2 days", $firstday))?></td>
			<td class="thursday">Thursday<br/><?=date("Y-m-d", strtotime("+3 days", $firstday))?></td>
			<td class="friday">Friday<br/><?=date("Y-m-d", strtotime("+4 days", $firstday))?></td>
			<td class="saturday red">Saturday<br/><?=date("Y-m-d", strtotime("+5 days", $firstday))?></td>
			<td class="sunday red">Sunday<br/><?=date("Y-m-d", strtotime("+6 days", $firstday))?></td>
		</tr>
	<?
		for ($n = 0; $n < 24; $n++)
		{
			$hour = str_pad("$n", 2, " ", STR_PAD_LEFT);
			?>
			<tr class="row">
				<td class="time_top">
					<?=$hour?>:00
				</td>
				<? if ($n == 0) { ?>
				<td class="day" rowspan="48">
				<?
					$time_now = $firstday;
					$day_events = $calendar->getEvents($week_events, $time_now, 60*60*24);
					foreach ($day_events as $de)
					{
						echo "<div class=\"event\" style=\"background-color: ".$de->rand_color.";\">".cmd($de->getName(), "exec=show&node_id=".$de->getNodeId())."</div>";
					}
				?>
				</td>
				<td class="day" rowspan="48">
				<?
					$time_now = strtotime("+1 day", $firstday);
					$day_events = $calendar->getEvents($week_events, $time_now, 60*60*24);
					foreach ($day_events as $de)
					{
						echo "<div class=\"event\" style=\"background-color: ".$de->rand_color.";\">".cmd($de->getName(), "exec=show&node_id=".$de->getNodeId())."</div>";
					}
				?>
				</td>
				<td class="day" rowspan="48">
				<?
					$time_now = strtotime("+2 day", $firstday);
					$day_events = $calendar->getEvents($week_events, $time_now, 60*60*24);
					foreach ($day_events as $de)
					{
						echo "<div class=\"event\" style=\"background-color: ".$de->rand_color.";\">".cmd($de->getName(), "exec=show&node_id=".$de->getNodeId())."</div>";
					}
				?>
				</td>
				<td class="day" rowspan="48">
				<?
					$time_now = strtotime("+3 day", $firstday);
					$day_events = $calendar->getEvents($week_events, $time_now, 60*60*24);
					foreach ($day_events as $de)
					{
						echo "<div class=\"event\" style=\"background-color: ".$de->rand_color.";\">".cmd($de->getName(), "exec=show&node_id=".$de->getNodeId())."</div>";
					}
				?>
				</td>
				<td class="day" rowspan="48">
				<?
					$time_now = strtotime("+4 day", $firstday);
					$day_events = $calendar->getEvents($week_events, $time_now, 60*60*24);
					foreach ($day_events as $de)
					{
						echo "<div class=\"event\" style=\"background-color: ".$de->rand_color.";\">".cmd($de->getName(), "exec=show&node_id=".$de->getNodeId())."</div>";
					}
				?>
				</td>
				<td class="day" rowspan="48">
				<?
					$time_now = strtotime("+5 day", $firstday);
					$day_events = $calendar->getEvents($week_events, $time_now, 60*60*24);
					foreach ($day_events as $de)
					{
						echo "<div class=\"event\" style=\"background-color: ".$de->rand_color.";\">".cmd($de->getName(), "exec=show&node_id=".$de->getNodeId())."</div>";
					}
				?>
				</td>
				<td class="day" rowspan="48">
				<?
					$time_now = strtotime("+6 day", $firstday);
					$day_events = $calendar->getEvents($week_events, $time_now, 60*60*24);
					foreach ($day_events as $de)
					{
						echo "<div class=\"event\" style=\"background-color: ".$de->rand_color.";\">".cmd($de->getName(), "exec=show&node_id=".$de->getNodeId())."</div>";
					}
				?>
				</td>
				<? } ?>
			</tr>
			<tr class="row">
				<td class="time_bottom">
					&nbsp;
				</td>
			</tr>
		<?
		}
	?>
	</table>
</fieldset>