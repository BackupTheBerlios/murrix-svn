<?
$calendar = new mCalendar();

$first_week_day = date("w", $args['firstday'])-1;
if ($first_week_day == -1) // Sunday
	$first_week_day++;
	
$days_of_month = date("t", $args['firstday']);
	
$last_week_day = date("w", strtotime("+".($days_of_month-1)." days", $args['firstday']));
if ($last_week_day == 0) // Sunday
	$last_week_day = 7;

$days_to_show = $days_of_month+(7-$last_week_day)+$first_week_day;

$first_stamp = strtotime("-$first_week_day days", $args['firstday']);
$last_stamp = strtotime("+$days_to_show days", $first_stamp);
$month_events = $calendar->getEvents($args['events'], $first_stamp, $last_stamp-$first_stamp);
?>

<fieldset>
	<legend>
		<?=cmd(ucf(i18n(strtolower(date("F", $args['firstday']))))." ".date("Y", $args['firstday']), "exec=calendar&view=month&date=".date("Ymd", $args['firstday']), "link")?>
	</legend>

	<table class="big_month_table" cellspacing="1">
		<tr class="title_row">
			<td class="week">&nbsp;</td>
			<td class="monday"><?=ucf(i18n("monday"))?></td>
			<td class="tuesday"><?=ucf(i18n("tuesday"))?></td>
			<td class="wednesday"><?=ucf(i18n("wednesday"))?></td>
			<td class="thursday"><?=ucf(i18n("thursday"))?></td>
			<td class="friday"><?=ucf(i18n("friday"))?></td>
			<td class="saturday red"><?=ucf(i18n("saturday"))?></td>
			<td class="sunday red"><?=ucf(i18n("sunday"))?></td>
		</tr>
		<tr class="week_row">
			<?
			for ($n = 0; $n < $days_to_show; $n++)
			{
				$days = $n-$first_week_day;
				if ($days > 0)
					$days = "+$days";
				else
					$days = "-$days";
				
				$time_now = strtotime("$days days", $args['firstday']);
				
				if ($n%7 == 0)
				{
				?>
					</tr>
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
					
				?>
				<td class="<?=$class?>">
				<?
					$day_str = date("j", $time_now);
					if ($day_str == 1)
						$day_str .= " ".ucf(i18n(strtolower(date("F", $time_now))));
					
					?>
					<div style="border-bottom: 1px solid #e1e4e8;">
						<div style="float: right; font-size: 7px;">
							<?=(date("z", $time_now)+1)?>
						</div>
						<?=cmd($day_str, "exec=calendar&view=day&date=".date("Ymd", $time_now), $link_class)?>
					</div>
					<?
						$day_events = $calendar->getEvents($month_events, $time_now, 60*60*24);
						foreach ($day_events as $de)
						{
							echo "<div class=\"event\" style=\"background-color: ".$de->rand_color.";\">".cmd($de->getName(), "exec=show&node_id=".$de->getNodeId())."</div>";
						}
					?>
				</td>
			<?
			}
		?>
		</tr>
	</table>
</fieldset>