<?
global $abspath, $wwwpath;

echo externcmd(img(geticon("global"))." ".ucf(i18n("external link here")), $_SESSION['murrix']['lastcmd'], "externlink");

?><div id="clear"></div><?

$right = $center = "";
$left = img(geticon("date"))."&nbsp;".ucf(i18n("calendar"));
include(gettpl("big_title"));

?>
<div class="calendar_head">
	<?=cmd(img(imgpath("up.png")), "Exec('calendar', 'zone_main', Hash('date', '".date("Y-m-d", strtotime("$date -1 week"))."'))")?>
	<?=ucf(i18n("week"))?>
	<?=cmd(img(imgpath("down.png")), "Exec('calendar', 'zone_main', Hash('date', '".date("Y-m-d", strtotime("$date +1 week"))."'))")?>
	�
	<?=cmd(img(imgpath("up.png")), "Exec('calendar', 'zone_main', Hash('date', '".date("Y-m-d", strtotime("$date -1 month"))."'))")?>
	<?=ucf(i18n("month"))?>
	<?=cmd(img(imgpath("down.png")), "Exec('calendar', 'zone_main', Hash('date', '".date("Y-m-d", strtotime("$date +1 month"))."'))")?>
	�
	<?=cmd(img(imgpath("up.png")), "Exec('calendar', 'zone_main', Hash('date', '".date("Y-m-d", strtotime("$date -1 year"))."'))")?>
	<?=ucf(i18n("year"))?>
	<?=cmd(img(imgpath("down.png")), "Exec('calendar', 'zone_main', Hash('date', '".date("Y-m-d", strtotime("$date +1 year"))."'))")?>
</div>

<table class="calendar">
	<tr>
		<td>
			&nbsp;
		</td>
		<td style="width: 14%;" class="head">
			<?=ucf(i18n("monday"))?>
		</td>
		<td style="width: 14%;" class="head">
			<?=ucf(i18n("tuesday"))?>
		</td>
		<td style="width: 14%;" class="head">
			<?=ucf(i18n("wednesday"))?>
		</td>
		<td style="width: 14%;" class="head">
			<?=ucf(i18n("thursday"))?>
		</td>
		<td style="width: 14%;" class="head">
			<?=ucf(i18n("friday"))?>
		</td>
		<td style="width: 14%;" class="head">
			<?=ucf(i18n("saturday"))?>
		</td>
		<td style="width: 14%;" class="head">
			<?=ucf(i18n("sunday"))?>
		</td>
	</tr>
	<?
	$calendar = new Calendar();

	for ($n = 0; $n <= 5; $n++)
	{
		$calendar->drawWeek($calendar->getWeek(date("Y-m-d", strtotime("$date +$n weeks"))));
	}
	?>
</table>

