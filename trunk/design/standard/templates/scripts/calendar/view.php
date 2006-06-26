<?
global $abspath, $wwwpath;

$right = $center = "";
$left = img(geticon("date"))."&nbsp;".ucf(i18n("calendar"));
include(gettpl("big_title"));

$calendar = new Calendar();
$events = $calendar->getAllEvents();
?>

<table class="calendar_table" cellspacing="0">
	<tr>
		<td class="left">
			<?
			$firstday = strtotime(date("Y-m", strtotime($date))."-01");
			for ($p = 1; $p <= 3; $p++)
			{
			?>
				<div class="container">
					<?include(gettpl("scripts/calendar/small_month"))?>
				</div>
				<?
				$firstday = strtotime("+$days_of_month days", $firstday);
			}
			$right = $center = "";
			$left = ucf(i18n("calendars"));
			include(gettpl("medium_title"));
			?>
			<div class="main">
			
			</div>
		</td>
		<td class="right">
			<div class="main">
				<?=ucf(i18n("view"))?>
				[ <?=($view == "day" ? ucf(i18n("day")) : cmd(ucf(i18n("day")), "exec=calendar&view=day&date=$date"))?> ]
				[ <?=($view == "week" ? ucf(i18n("week")) : cmd(ucf(i18n("week")), "exec=calendar&view=week&date=$date"))?> ]
				[ <?=($view == "month" ? ucf(i18n("month")) : cmd(ucf(i18n("month")), "exec=calendar&view=month&date=$date"))?> ]
				|
				[ <?=cmd(ucf(i18n("goto today")), "exec=calendar&view=$view&date=".date("Ymd"))?> ]
				·
				<?=cmd(img(imgpath("left.png")), "exec=calendar&view=$view&date=".date("Ymd", strtotime("-1 week", strtotime($date))))?>
				<?=ucf(i18n("week"))?>
				<?=cmd(img(imgpath("right.png")), "exec=calendar&view=$view&date=".date("Ymd", strtotime("+1 week", strtotime($date))))?>
				·
				<?=cmd(img(imgpath("left.png")), "exec=calendar&date=".date("Ymd", strtotime("-1 month", strtotime($date))))?>
				<?=ucf(i18n("month"))?>
				<?=cmd(img(imgpath("right.png")), "exec=calendar&view=$view&date=".date("Ymd", strtotime("+1 month", strtotime($date))))?>
				·
				<?=cmd(img(imgpath("left.png")), "exec=calendar&view=$view&date=".date("Ymd", strtotime("-1 year", strtotime($date))))?>
				<?=ucf(i18n("year"))?>
				<?=cmd(img(imgpath("right.png")), "exec=calendar&view=$view&date=".date("Ymd", strtotime("+1 year", strtotime($date))))?>
			</div>
			
			<div class="container">
			<?
				switch ($view)
				{
					case "month":
					$firstday = strtotime(date("Y-m", strtotime($date))."-01");
					include(gettpl("scripts/calendar/month_view"));
					break;
					
					case "week":
					$firstday = strtotime($date);
					include(gettpl("scripts/calendar/week_view"));
					break;
					
					case "day":
					$firstday = strtotime($date);
					include(gettpl("scripts/calendar/day_view"));
					break;
				}
			?>
			</div>
		</td>
	</tr>
</table><?/*

<div class="calendar_head">
	<div class="left">
		<?=ucf(i18n("today's date")).": ".date("Y-m-d")?>
		<br/>
		<?=ucf(i18n("calendar's date")).": $date"?>
	</div>
	<div class="right">
		<?=cmd(img(imgpath("left.png")), "exec=calendar&date=".date("Y-m-d", strtotime("$date -1 week")))?>
		<?=ucf(i18n("week"))?>
		<?=cmd(img(imgpath("right.png")), "exec=calendar&date=".date("Y-m-d", strtotime("$date +1 week")))?>
		·
		<?=cmd(img(imgpath("left.png")), "exec=calendar&date=".date("Y-m-d", strtotime("$date -1 month")))?>
		<?=ucf(i18n("month"))?>
		<?=cmd(img(imgpath("right.png")), "exec=calendar&date=".date("Y-m-d", strtotime("$date +1 month")))?>
		·
		<?=cmd(img(imgpath("left.png")), "exec=calendar&date=".date("Y-m-d", strtotime("$date -1 year")))?>
		<?=ucf(i18n("year"))?>
		<?=cmd(img(imgpath("right.png")), "exec=calendar&date=".date("Y-m-d", strtotime("$date +1 year")))?>
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

*/?>