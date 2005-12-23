<?
global $abspath, $wwwpath;
?>

<div id="startpage">
	<table width="100%" cellspacing="0">
		<tr>
			<td class="tdtop">
				<?
				$welcome = new mObject(resolvePath("/Root/Etek/Hidden/Startpage/Welcome"));
				$left = $right = "";
				$center = "<span style=\"font-weight: bold;\">".cmd($welcome->getName(), "Exec('show','zone_main', Hash('path', '".$welcome->getPath()."'))")."</span>";

				include(gettpl("medium_title"));
				?>
				<div class="main">
					<?=$welcome->getVarValue("text", true)?>
				</div>
				
				<table width="100%" cellspacing="0">
					<tr>
						<td class="tdtop" width="50%">
						<?
							$important = new mObject(resolvePath("/Root/Etek/Hidden/Startpage/Important"));
							$left = $right = "";
							$center = "<span style=\"font-weight: bold;\">".cmd($important->getName(), "Exec('show','zone_main', Hash('path', '".$important->getPath()."'))")."</span>";
			
							include(gettpl("medium_title"));
							?>
							<div class="main">
								<?
								echo $important->getVarValue("text", true);
								?>
							</div>
						</td>
						<td class="tdtop" width="50%">
							<?
							$event_obj = new mObject(resolvePath("/Root/Etek/Hidden/Events"));
							$left = $right = "";
							$center = "<span style=\"font-weight: bold;\">".cmd($event_obj->getName(), "Exec('show','zone_main', Hash('path', '".$event_obj->getPath()."'))")."</span>";
			
							include(gettpl("medium_title"));
							?>
							<div class="main">
								<div class="news_category">
									<table width="100%" cellspacing="0">
										<tr>
											<td>
												<?=ucfirst(i18n("today"))?>
											</td>
											<td align="right">
											<?
												/*if (HasRight("create", $event_obj->GetPath(), array("event")))
												{
													echo "<a class=\"\" onclick=\"SystemRunScript('create_object','zone_main', Hash('path', '".$event_obj->GetPath()."', 'class', 'event'));\" href=\"javascript:void(null);\">";
													echo img(geticon("date"));
													echo "&nbsp;";
													echo "New event";
													echo "</a>";
												}*/
											?>
											</td>
										</tr>
									</table>
								</div>
								<hr style="border: 0; color: #FCE464; background-color: #FCE464; height: 1px; margin: 0;"/>
								<?
								/*
								$today = date("Y-m-d");
								for ($n = 0; $n < count($events); $n++)
								{
									$item = $events[$n];
									if ($item->GetValue("date") != $today)
										break;
									echo "<a class=\"\" onclick=\"SystemRunScript('show','zone_main', Hash('path', '".$item->GetPath()."'));\" href=\"javascript:void(null);\">".img(geticon($item->GetIcon()))." $item->name</a><br/>";
								}*/
								?>
								<br/>
								<div class="news_category">
									<table width="100%" cellspacing="0">
										<tr>
											<td>
												<?=ucfirst(i18n("comming up"))?>
											</td>
											<td align="right">
											<?
												/*if (HasRight("create", $event_obj->GetPath(), array("event")))
												{
													echo "<a class=\"\" onclick=\"SystemRunScript('create_object','zone_main', Hash('path', '".$event_obj->GetPath()."', 'class', 'event'));\" href=\"javascript:void(null);\">";
													echo img(geticon("date"));
													echo "&nbsp;";
													echo "New event";
													echo "</a>";
												}*/
											?>
											</td>
										</tr>
									</table>
								</div>
								<hr style="border: 0; color: #FCE464; background-color: #FCE464; height: 1px; margin: 0;"/>
								<?
								/*for ($n; $n < count($events); $n++)
								{
									$item = $events[$n];
									echo "<b>".$item->GetValue("date")."</b> <a class=\"\" onclick=\"SystemRunScript('show','zone_main', Hash('path', '".$item->GetPath()."'));\" href=\"javascript:void(null);\">".img(geticon($item->GetIcon()))." $item->name</a><br/>";
								}*/
								?>
							</div>
						</td>
					</tr>
				</table>
			</td>
			<td class="tdtop" width="350">
				<?
				$news_obj = new mObject(resolvePath("/Root/Etek/Hidden/News"));
				$cat_objs = fetch("FETCH node WHERE link:node_top='".$news_obj->getNodeId()."' AND link:type='sub' NODESORTBY !property:version SORTBY property:name");
							
				$left = $right = "";
				$center = "<span style=\"font-weight: bold;\">".cmd($news_obj->getName(), "Exec('show','zone_main', Hash('path', '".$news_obj->getPath()."'))")."</span>";

				include(gettpl("medium_title"));
				?>
				<div class="main">
				<?
					$num = count($cat_objs);
					$count = 0;
					foreach ($cat_objs as $category)
					{
					?>
						<div class="news_category">
							<table width="100%" cellspacing="0">
								<tr>
									<td>
										<?=cmd(img(geticon($category->getIcon()))." ".$category->getName(), "Exec('show','zone_main', Hash('path', '".$category->getPath()."'))")?>
									</td>
									<td align="right">
									<?
										if ($object->hasRight("create_subnodes", array("news")))
										{
											echo cmd(img(geticon("news"))."&nbsp;".ucfirst(i18n("post news")), "Exec('new','zone_main', Hash('path', '".$category->getPath()."', 'class_name', 'news'))");
										}
									?>
									</td>
								</tr>
							</table>
						</div>
						<hr style="border: 0; color: #FCE464; background-color: #FCE464; height: 1px; margin: 0;"/>
						<?
						$news = fetch("FETCH node WHERE link:node_top='".$category->getNodeId()."' AND link:type='sub' AND property:class_name='news' NODESORTBY !property:version SORTBY var:date");

						foreach ($news as $item)
						{
							$parts = explode(" ", $item->getCreated());
							echo "<b>".$parts[0]."</b> ";
							echo cmd(img(geticon($item->getIcon()))." ".$item->getName(), "Exec('show','zone_main', Hash('path', '".$item->getPath()."'))");
							echo "<br/>";
						}

						$count++;

						if ($count != $num)
							echo "<br/>";
					}
				?>
				</div>
			</td>
		</tr>
	</table>
</div>
