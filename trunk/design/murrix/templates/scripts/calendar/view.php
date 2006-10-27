<?
echo compiletpl("scripts/calendar/tabs", array("view"=>$args['view']));

echo compiletpl("title/big", array("left"=>img(geticon("date"))."&nbsp;".ucf(i18n("calendar"))));

?>

<table class="calendar_table" cellspacing="0">
	<tr>
		<?
			/*$firstday = strtotime(date("Y-m", strtotime($args['date']))."-01");
			for ($p = 1; $p <= 3; $p++)
			{
				$args_small = array("events"=>$args['events'],"firstday"=>$firstday);
			?>
				<div class="container">
					<?=compiletplWithOutput("scripts/calendar/small_month", $args_small)?>
				</div>
				<?
				$firstday = strtotime("+1 month", $firstday);
			}
			
			echo compiletpl("title/medium", array("left"=>ucf(i18n("calendars"))));
			
			foreach ($args['calendars'] as $name => $list)
			{
			?>
				<div class="container">
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
				</div>
			<?
			}*/
		?>
		<td class="right">
			<fieldset>
				<legend>
					<?=ucf(i18n("navigation"))?>
				</legend>
					
				[ <?=cmd(ucf(i18n("goto today")), "exec=calendar&view=".$args['view']."&date=".date("Ymd"))?> ]
				·
				<?=cmd(img(imgpath("left.png")), "exec=calendar&view=".$args['view']."&date=".date("Ymd", strtotime("-1 week", strtotime($args['date']))))?>
				<?=ucf(i18n("week"))?>
				<?=cmd(img(imgpath("right.png")), "exec=calendar&view=".$args['view']."&date=".date("Ymd", strtotime("+1 week", strtotime($args['date']))))?>
				·
				<?=cmd(img(imgpath("left.png")), "exec=calendar&view=".$args['view']."&date=".date("Ymd", strtotime("-1 month", strtotime($args['date']))))?>
				<?=ucf(i18n("month"))?>
				<?=cmd(img(imgpath("right.png")), "exec=calendar&view=".$args['view']."&date=".date("Ymd", strtotime("+1 month", strtotime($args['date']))))?>
				·
				<?=cmd(img(imgpath("left.png")), "exec=calendar&view=".$args['view']."&date=".date("Ymd", strtotime("-1 year", strtotime($args['date']))))?>
				<?=ucf(i18n("year"))?>
				<?=cmd(img(imgpath("right.png")), "exec=calendar&view=".$args['view']."&date=".date("Ymd", strtotime("+1 year", strtotime($args['date']))))?>
			</fieldset>
				<div style="margin-right: 20px;">
				<?
				$firstday = strtotime(date("Y-m", strtotime($args['date']))."-01");
				for ($p = 1; $p <= 3; $p++)
				{
					$args_small = array("events"=>$args['events'],"firstday"=>$firstday);
				?>
					<div style="float: left; display: inline; width: 150px; margin-right: 10px;">
						<?=compiletplWithOutput("scripts/calendar/small_month", $args_small)?>
					</div>
					<?
					$firstday = strtotime("+1 month", $firstday);
				}
				?>
				</div>
			<div class="clear"></div>
			<div id="calendar_main_zone">
			<?
				$data = $_SESSION['murrix']['system']->getZoneData("calendar_main_zone");
				if (!empty($data))
					echo $data;
				else
				{
					switch ($args['view'])
					{
						case "month":
						echo compiletpl("scripts/calendar/month_view", array("date"=>$args['date'], "calendars"=>$args['calendars'], "view"=>$args['view'], "events"=>$args['events'], "firstday"=>strtotime(date("Y-m", strtotime($args['date']))."-01")));
						break;
						
						case "week":
						echo compiletpl("scripts/calendar/week_view", array("date"=>$args['date'], "calendars"=>$args['calendars'], "view"=>$args['view'], "events"=>$args['events'], "firstday"=>strtotime($args['date'])));
						break;
						
						case "day":
						echo compiletpl("scripts/calendar/day_view", array("date"=>$args['date'], "calendars"=>$args['calendars'], "view"=>$args['view'], "events"=>$args['events'], "firstday"=>strtotime($args['date'])));
						break;
					}
				}
			?>
			</div>
			<?
			foreach ($args['calendars'] as $name => $list)
			{
			?>
				<div class="container">
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
							
							
								<input disabled onclick="document.getElementById('calendar<?=$item->getNodeId()?>').checked=!document.getElementById('calendar<?=$item->getNodeId()?>').checked;Exec('calendar',Hash('toggle','<?=$item->getNodeId()?>'))" id="calendar<?=$item->getNodeId()?>" class="input" type="checkbox" <?=($item->active ? "checked" : "")?>/><?=img(geticon($item->getIcon()))?> <?=ucf($item->getName())?>
							</div>
						<?
						}
					?>
					</fieldset>
				</div>
			<?
			}
		?>
		</td>
	</tr>
</table>