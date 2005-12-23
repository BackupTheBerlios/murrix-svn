<?
global $abspath, $wwwpath;

$right = $center = "";
$left = "<span style=\"font-weight: bold; font-size: 18px;\">".img(geticon("date"))."&nbsp;".ucf(i18n("calendar"))."</span>";
include(gettpl("big_title"));

?>
<div class="main_bg" style="text-align: center; margin-top: 5px;">
	<div class="main">
		<?=cmd(img(imgpath("up.png")), "Exec('calendar', 'zone_main', Hash('date', '".date("Y-m-d", strtotime("$date -1 week"))."'))")?>
		<?=ucf(i18n("week"))?>
		<?=cmd(img(imgpath("down.png")), "Exec('calendar', 'zone_main', Hash('date', '".date("Y-m-d", strtotime("$date +1 week"))."'))")?>
		·
		<?=cmd(img(imgpath("up.png")), "Exec('calendar', 'zone_main', Hash('date', '".date("Y-m-d", strtotime("$date -1 month"))."'))")?>
		<?=ucf(i18n("month"))?>
		<?=cmd(img(imgpath("down.png")), "Exec('calendar', 'zone_main', Hash('date', '".date("Y-m-d", strtotime("$date +1 month"))."'))")?>
		·
		<?=cmd(img(imgpath("up.png")), "Exec('calendar', 'zone_main', Hash('date', '".date("Y-m-d", strtotime("$date -1 year"))."'))")?>
		<?=ucf(i18n("year"))?>
		<?=cmd(img(imgpath("down.png")), "Exec('calendar', 'zone_main', Hash('date', '".date("Y-m-d", strtotime("$date +1 year"))."'))")?>
	</div>
</div>

<table class="invisible" cellspacing="0" width="100%">
	<tr>
		<td>
			&nbsp;
		</td>
		<td style="width: 14%;">
			<div class="main_bg" style="text-align: center; margin-top: 5px;">
				<div class="main">
					<?=ucf(i18n("monday"))?>
				</div>
			</div>
		</td>
		<td style="width: 14%;">
			<div class="main_bg" style="text-align: center; margin-top: 5px;">
				<div class="main">
					<?=ucf(i18n("tuesday"))?>
				</div>
			</div>
		</td>
		<td style="width: 14%;">
			<div class="main_bg" style="text-align: center; margin-top: 5px;">
				<div class="main">
					<?=ucf(i18n("wednesday"))?>
				</div>
			</div>
		</td>
		<td style="width: 14%;">
			<div class="main_bg" style="text-align: center; margin-top: 5px;">
				<div class="main">
					<?=ucf(i18n("thursday"))?>
				</div>
			</div>
		</td>
		<td style="width: 14%;">
			<div class="main_bg" style="text-align: center; margin-top: 5px;">
				<div class="main">
					<?=ucf(i18n("friday"))?>
				</div>
			</div>
		</td>
		<td style="width: 14%;">
			<div class="main_bg" style="text-align: center; margin-top: 5px;">
				<div class="main" style="color: red;">
					<?=ucf(i18n("saturday"))?>
				</div>
			</div>
		</td>
		<td style="width: 14%;">
			<div class="main_bg" style="text-align: center; margin-top: 5px;">
				<div class="main" style="color: red;">
					<?=ucf(i18n("sunday"))?>
				</div>
			</div>
		</td>
	</tr>
	<?
	$calendar = new Calendar();

	for ($n = 0; $n <= 5; $n++)
	{
		cal_drawWeek($calendar->getWeek(date("Y-m-d", strtotime("$date +$n weeks"))));
	}
	?>
</table>

