<fieldset>
	<legend>
		<?=cmd(ucf(i18n(strtolower(date("F", $args['firstday']))))." ".date("Y", $args['firstday']), "exec=calendar&view=month&date=".date("Ymd", $args['firstday']), "link")?>
	</legend>

	<?=compiletpl("scripts/calendar/small_month_inner", $args)?>
</fieldset>