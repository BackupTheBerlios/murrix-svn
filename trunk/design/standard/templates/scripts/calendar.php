<?
global $abspath, $wwwpath;

$right = $center = "";
$left = img(geticon("date"))."&nbsp;".ucf(i18n("calendar"));
include(gettpl("big_title"));

?>
<div class="calendar_head">
	<div class="left">
		<?=ucf(i18n("today's date")).": ".date("Y-m-d")?>
		<br/>
		<?=ucf(i18n("calendar's date")).": $date"?>
	</div>
	<div class="right">
		<?=cmd(img(imgpath("left.png")), "Exec('calendar','zone_main',Hash('date','".date("Y-m-d", strtotime("$date -1 week"))."'))")?>
		<?=ucf(i18n("week"))?>
		<?=cmd(img(imgpath("right.png")), "Exec('calendar','zone_main',Hash('date','".date("Y-m-d", strtotime("$date +1 week"))."'))")?>
		·
		<?=cmd(img(imgpath("left.png")), "Exec('calendar','zone_main',Hash('date','".date("Y-m-d", strtotime("$date -1 month"))."'))")?>
		<?=ucf(i18n("month"))?>
		<?=cmd(img(imgpath("right.png")), "Exec('calendar','zone_main',Hash('date','".date("Y-m-d", strtotime("$date +1 month"))."'))")?>
		·
		<?=cmd(img(imgpath("left.png")), "Exec('calendar','zone_main',Hash('date','".date("Y-m-d", strtotime("$date -1 year"))."'))")?>
		<?=ucf(i18n("year"))?>
		<?=cmd(img(imgpath("right.png")), "Exec('calendar','zone_main',Hash('date','".date("Y-m-d", strtotime("$date +1 year"))."'))")?>
	</div>
	<div class="clear"></div>
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
	
	$calendar = new Calendar($events);

	for ($n = 0; $n < 5; $n++)
	{
		$calendar->drawWeek($calendar->getWeek(date("Y-m-d", strtotime("$date +$n weeks"))));
	}
	?>
</table>

