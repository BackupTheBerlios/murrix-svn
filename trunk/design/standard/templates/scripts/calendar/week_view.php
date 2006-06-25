<?


?>

<fieldset>
	<legend>
		<?=cmd(ucf(i18n("week"))." ".date("W", $firstday)." ".date("Y", $firstday), "exec=calendar&view=week&date=".date("Ymd", $firstday), "link")?>
	</legend>

	<table class="big_week_table" cellspacing="1">
		<tr class="title_row">
			<td class="time">&nbsp;</td>
			<td class="monday">Monday</td>
			<td class="tuesday">Tuesday</td>
			<td class="wednesday">Wednesday</td>
			<td class="thursday">Thursday</td>
			<td class="friday">Friday</td>
			<td class="saturday red">Saturday</td>
			<td class="sunday red">Sunday</td>
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
				<td class="day">
				
				</td>
				<td class="day">
				
				</td>
				<td class="day">
				
				</td>
				<td class="day">
				
				</td>
				<td class="day">
				
				</td>
				<td class="day">
				
				</td>
				<td class="day">
				
				</td>
			</tr>
			<tr class="row">
				<td class="time_bottom">
					&nbsp;
				</td>
				<td class="day">
				
				</td>
				<td class="day">
				
				</td>
				<td class="day">
				
				</td>
				<td class="day">
				
				</td>
				<td class="day">
				
				</td>
				<td class="day">
				
				</td>
				<td class="day">
				
				</td>
			</tr>
		<?
		}
	?>
	</table>
</fieldset>