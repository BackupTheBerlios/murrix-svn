<?
echo compiletpl("title/big", array("left"=>img(geticon("date"))."&nbsp;".ucf(i18n("calendar"))));
?>

<table class="calendar_table" cellspacing="0">
	<tr>
		<td class="left">
		<?
			$firstday = strtotime(date("Y-m", strtotime($args['date']))."-01");
			for ($p = 1; $p <= 3; $p++)
			{
			?>
				<div class="container">
					<?=compiletpl("scripts/calendar/small_month", array("events"=>$args['events'],"firstday"=>$firstday))?>
				</div>
				<?
				$firstday = strtotime("+$days_of_month days", $firstday);
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
			}
		?>
		</td>
		<td class="right">
			<div class="main">
				<div class="container">
					<?=ucf(i18n("view"))?>
					[ <?=($args['view'] == "day" ? ucf(i18n("day")) : cmd(ucf(i18n("day")), "exec=calendar&view=day&date=".$args['date']))?> ]
					[ <?=($args['view'] == "week" ? ucf(i18n("week")) : cmd(ucf(i18n("week")), "exec=calendar&view=week&date=".$args['date']))?> ]
					[ <?=($args['view'] == "month" ? ucf(i18n("month")) : cmd(ucf(i18n("month")), "exec=calendar&view=month&date=".$args['date']))?> ]
					·
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
				</div>
			</div>
			
			<div class="container">
				<div id="calendar_main_zone">
				<?
					switch ($args['view'])
					{
						case "month":
						echo compiletpl("scripts/calendar/month_view", array("date"=>$this->date, "calendars"=>$this->calendars, "view"=>$this->view, "events"=>$args['events'], "firstday"=>strtotime(date("Y-m", strtotime($args['date']))."-01")));
						break;
						
						case "week":
						echo compiletpl("scripts/calendar/week_view", array("date"=>$this->date, "calendars"=>$this->calendars, "view"=>$this->view, "events"=>$args['events'], "firstday"=>strtotime($args['date'])));
						break;
						
						case "day":
						echo compiletpl("scripts/calendar/day_view", array("date"=>$this->date, "calendars"=>$this->calendars, "view"=>$this->view, "events"=>$args['events'], "firstday"=>strtotime($args['date'])));
						break;
					}
				?>
				</div>
			</div>
		</td>
	</tr>
</table>