<?

$last_stamp = strtotime("+1 day", $firstday);
$day_events = $calendar->getEvents($events, $firstday, $last_stamp-$firstday);
?>

<fieldset>
	<legend>
		<?=cmd(ucf(i18n(strtolower(date("l", $firstday))))." ".date("d", $firstday)." ".ucf(i18n(strtolower(date("F", $firstday))))." ".date("Y", $firstday), "exec=calendar&view=day&date=".date("Ymd", $firstday), "link")?>
	</legend>

	<table class="big_day_table" cellspacing="1">
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
				<td class="day_piece" rowspan="48">
				<?
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