<?


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
				<td class="day_piece">
				
				</td>
			</tr>
			<tr class="row">
				<td class="time_bottom">
					&nbsp;
				</td>
				<td class="day_piece">
				
				</td>
			</tr>
		<?
		}
	?>
	</table>
</fieldset>