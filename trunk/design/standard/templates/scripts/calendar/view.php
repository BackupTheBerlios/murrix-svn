<?
$right = $center = "";
$left = img(geticon("date"))."&nbsp;".ucf(i18n("calendar"));
include(gettpl("big_title"));
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
			
			foreach ($calendars as $name => $list)
			{
			?>
				<fieldset>
					<legend>
						<?=ucf($name)?>
					</legend>
					<?
					foreach ($list as $item)
					{
					?>
						<div onclick="document.getElementById('calendar<?=$item->getNodeId()?>').checked=!document.getElementById('calendar<?=$item->getNodeId()?>').checked;Exec('calendar',Hash('toggle','<?=$item->getNodeId()?>'))" style="cursor: pointer; margin-bottom: 2px;">
							<div style="float: right; background-color: <?=$item->color?>; width: 22px; height: 22px;"></div>
						
						
							<input onclick="document.getElementById('calendar<?=$item->getNodeId()?>').checked=!document.getElementById('calendar<?=$item->getNodeId()?>').checked;Exec('calendar',Hash('toggle','<?=$item->getNodeId()?>'))" id="calendar<?=$item->getNodeId()?>" class="input" type="checkbox" <?=($item->active ? "checked" : "")?>/><?=img(geticon($item->getIcon()))?> <?=ucf($item->getName())?>
						</div>
					<?
					}
				?>
				</fieldset>
			<?
			}
		?>
		</td>
		<td class="right">
			<div class="main">
				<div class="container">
					<?=ucf(i18n("view"))?>
					[ <?=($view == "day" ? ucf(i18n("day")) : cmd(ucf(i18n("day")), "exec=calendar&view=day&date=$date"))?> ]
					[ <?=($view == "week" ? ucf(i18n("week")) : cmd(ucf(i18n("week")), "exec=calendar&view=week&date=$date"))?> ]
					[ <?=($view == "month" ? ucf(i18n("month")) : cmd(ucf(i18n("month")), "exec=calendar&view=month&date=$date"))?> ]
					·
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
			</div>
			
			<div class="container">
				<div id="calendar_main_zone">
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
			</div>
		</td>
	</tr>
</table>