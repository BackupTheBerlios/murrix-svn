<?
$calendar = new mCalendar();
$last_stamp = strtotime("+1 week", $args['firstday']);
$week_events = $calendar->getEvents($args['events'], $args['firstday'], $last_stamp-$args['firstday']);
?>

<fieldset>
	<legend>
		<?=cmd(ucf(i18n("week"))." ".date("W", $args['firstday'])." ".date("Y", $args['firstday']), "exec=calendar&view=week&date=".date("Ymd", $args['firstday']), "link")?>
	</legend>

	<table class="big_week_table" cellspacing="1">
		<tr class="title_row">
			<td class="time">&nbsp;</td>
			<td class="monday"><?=ucf(i18n("monday"))?><br/><?=date("Y-m-d", $args['firstday'])?></td>
			<td class="tuesday"><?=ucf(i18n("tuesday"))?><br/><?=date("Y-m-d", strtotime("+1 days", $args['firstday']))?></td>
			<td class="wednesday"><?=ucf(i18n("wednesday"))?><br/><?=date("Y-m-d", strtotime("+2 days", $args['firstday']))?></td>
			<td class="thursday"><?=ucf(i18n("thursday"))?><br/><?=date("Y-m-d", strtotime("+3 days", $args['firstday']))?></td>
			<td class="friday"><?=ucf(i18n("friday"))?><br/><?=date("Y-m-d", strtotime("+4 days", $args['firstday']))?></td>
			<td class="saturday red"><?=ucf(i18n("saturday"))?><br/><?=date("Y-m-d", strtotime("+5 days", $args['firstday']))?></td>
			<td class="sunday red"><?=ucf(i18n("sunday"))?><br/><?=date("Y-m-d", strtotime("+6 days", $args['firstday']))?></td>
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
					$time_now = $args['firstday'];
					$day_events = $calendar->getEvents($week_events, $time_now, 60*60*24);
					foreach ($day_events as $de)
					{
						echo "<div class=\"event\" style=\"background-color: ".$de->rand_color.";\">".cmd($de->getName(), "exec=show&node_id=".$de->getNodeId())."</div>";
					}
				?>
				</td>
				<td class="day" rowspan="48">
				<?
					$time_now = strtotime("+1 day", $args['firstday']);
					$day_events = $calendar->getEvents($week_events, $time_now, 60*60*24);
					foreach ($day_events as $de)
					{
						echo "<div class=\"event\" style=\"background-color: ".$de->rand_color.";\">".cmd($de->getName(), "exec=show&node_id=".$de->getNodeId())."</div>";
					}
				?>
				</td>
				<td class="day" rowspan="48">
				<?
					$time_now = strtotime("+2 day", $args['firstday']);
					$day_events = $calendar->getEvents($week_events, $time_now, 60*60*24);
					foreach ($day_events as $de)
					{
						echo "<div class=\"event\" style=\"background-color: ".$de->rand_color.";\">".cmd($de->getName(), "exec=show&node_id=".$de->getNodeId())."</div>";
					}
				?>
				</td>
				<td class="day" rowspan="48">
				<?
					$time_now = strtotime("+3 day", $args['firstday']);
					$day_events = $calendar->getEvents($week_events, $time_now, 60*60*24);
					foreach ($day_events as $de)
					{
						echo "<div class=\"event\" style=\"background-color: ".$de->rand_color.";\">".cmd($de->getName(), "exec=show&node_id=".$de->getNodeId())."</div>";
					}
				?>
				</td>
				<td class="day" rowspan="48">
				<?
					$time_now = strtotime("+4 day", $args['firstday']);
					$day_events = $calendar->getEvents($week_events, $time_now, 60*60*24);
					foreach ($day_events as $de)
					{
						echo "<div class=\"event\" style=\"background-color: ".$de->rand_color.";\">".cmd($de->getName(), "exec=show&node_id=".$de->getNodeId())."</div>";
					}
				?>
				</td>
				<td class="day" rowspan="48">
				<?
					$time_now = strtotime("+5 day", $args['firstday']);
					$day_events = $calendar->getEvents($week_events, $time_now, 60*60*24);
					foreach ($day_events as $de)
					{
						echo "<div class=\"event\" style=\"background-color: ".$de->rand_color.";\">".cmd($de->getName(), "exec=show&node_id=".$de->getNodeId())."</div>";
					}
				?>
				</td>
				<td class="day" rowspan="48">
				<?
					$time_now = strtotime("+6 day", $args['firstday']);
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