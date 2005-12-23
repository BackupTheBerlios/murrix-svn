<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="shortcut icon" href="<?=geticon("etek", 16, "ico")?>" type="image/x-icon">
		<title>ElektroTeknologens Portal</title>
		
		<?
		$js = getjs();
		for ($i = 0; $i < count($js); $i++)
			echo "<script type=\"text/javascript\" src=\"".$js[$i]."\"></script>\n";

		$js = getcss();
		for ($i = 0; $i < count($js); $i++)
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$js[$i]."\">\n";

		$_SESSION['murrix']['System']->PrintHeader();
		?>
		<script type="text/javascript">
		<!--
			function loading(state)
			{
				if (state)
					document.getElementById('status').src = '<?=imgpath("quarl_load.png")?>';
				else
					document.getElementById('status').src = '<?=imgpath("quarl.png")?>';
			}
		// -->
		</script>
	</head>
	<body>
		<table width="100%" cellspacing="5" cellpadding="0">
			<tr>
				<td class="td_side">
				<!-- LEFT COLUMN -->
					<?=cmd("<img alt=\"Quarl Logo\" id=\"status\" src=\"".imgpath("quarl.png")."\"/>", "Exec('show', 'zone_main', Hash('path', '/Root/Etek/Public'));")?>
					<br/>
					<?
					$center = "<span style=\"font-weight: bold;\">".ucfirst(i18n("menu"))."</span>";
					$right = $left = "";
					include(gettpl("medium_title"));
					?>
					<div style="margin-top: 5px;">
						<ul class="menulist" id="listMenuRoot">
							<?=guiDrawTreeMenuRecursive(new mObject(ResolvePath("/Root/Etek/Public", "eng")))?>
						</ul>
					</div>
					
					<?
					$center = "<span style=\"font-weight: bold;\">".ucfirst(i18n("new forum posts"))."</span>";
					$right = $left = "";
					include(gettpl("medium_title"));
					?>
					<div style="margin-top: 5px;">
						<ul class="menulist">
							<li>
							<?
								$children = fetch("FETCH node WHERE property:class_name='forum_post' OR property:class_name='forum_thread' NODESORTBY !property:version SORTBY !property:created");
								
								foreach ($children as $child)
									include(gettpl("small_line", $child));
							?>
							</li>
						</ul>
					</div>
				</td>
				<td valign="top">
				<!-- MIDLLE COLUMN -->

					<?=cmd("<img alt=\"ElektroTeknologens Portal\" src=\"".imgpath("titel.jpg")."\"/>", "Exec('show','zone_main', Hash('path', '/Root/Etek/Public'));")?>
					
					<hr align="left" width="70%" size="1" style="margin: 0; margin-top: 2px;"/>
					<div id="zone_language" style="float:right; vertical-align:top;"></div>
					<table class="invisible" cellspacing="0">
						<tr>
							<td>
							<?
								echo "<i>".ucfirst(i18n("today")).":</i> ".date("j").":".($_SESSION['murrix']['language'] == "swe" ? "e" : date("S"))." ".i18n(strtolower(date("F")));
							?>
							</td>

							<td>
							<?
								echo cmd(img(geticon("date")), "Exec('calendar','zone_main', '')");
							?>
							</td>
							
							<?
							
							$calendar = new Calendar();

							for ($n = 0; $n < 3; $n++)
							{
								cal_drawHeadWeek($calendar->getWeek(date("Y-m-d", strtotime("$date +$n weeks"))));
								echo "<td>&nbsp;</td>";
							}
						?>
						</tr>
					</table>
					
					<table class="invisible_complete" cellspacing="0" cellpadding="0" style="width:100%">
						<tr>
							<td style="width:70%;">
								<div class="main_bg">
									<div id="zone_addressbar"></div>
								</div>
							</td>
							<td style="width:29%; padding-left:5px;">
								<div class="main_bg">
									<div id="zone_search">
										<form id="smallSearch" name="smallSearch" style="display: inline; margin:0px; padding:0px;" action="javascript:void(null);" onsubmit="Post('search', 'zone_main', 'smallSearch')">
											<table class="invisible_complete" cellspacing="0" cellpadding="0" style="width:100%">
												<tr>
													<td style="padding-left:2px; padding-right:2px">
														<?=img(geticon("search"))?>
													</td>
													<td style="width:100%; padding-right:2px">
														<input id="query" name="query" class="form_search" type="text" onfocus="if(this.value=='<?=ucfirst(i18n("enter search here"))?>!')this.value=''" onblur="if(this.value=='')this.value='<?=ucfirst(i18n("enter search here"))?>!'" value="<?=ucfirst(i18n("enter search here"))?>!"/>
													</td>
													<td>
														<input class="submit_search" type="submit" value="<?=ucfirst(i18n("search"))?>"/>
													</td>
												</tr>
											</table>
										</form>
									</div>
								</div>
							</td>
						</tr>
					</table>
					<div>
						<div id="zone_main"></div>
					</div>
					
					<? include(gettpl("footer")) ?>
					<center>
						<a href="http://validator.w3.org/check?uri=referer">
							<img src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Transitional" height="31" width="88">
						</a>
						<a href="http://jigsaw.w3.org/css-validator">
							<img src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!" height="31" width="88">
						</a>
					</center>
					
					<iframe src="history.php" id="history" name="history" width="0" height="0" style="display:none;"></iframe>
				</td>
				<td class="td_side">
				<!-- RIGHT COLUMN -->
					<br/>
					<div id="zone_login"></div>
					<br/>
					<?
					$quicklinks = new mObject(ResolvePath("/Root/Etek/Public/Link Library/Quicklinks", "eng"));

					$center = "<span style=\"font-weight: bold;\">".$quicklinks->getName()."</span>";
					$right = $left = "";
					include(gettpl("medium_title"));
					?>
					<div style="margin-top: 5px;">
						<ul class="menulist">
							<li>
							<?
								$children = fetch("FETCH node WHERE link:node_top='".$quicklinks->getNodeId()."' AND link:type='sub' AND property:class_name='link' NODESORTBY property:version SORTBY property:name");
								
								foreach ($children as $child)
									include(gettpl("small_line", $child));
							?>
							</li>
						</ul>
					</div>

					<?
					$period = 2;
					$center = "<span style=\"font-weight: bold;\">".ucfirst(i18n("courselinks"))."</span>";
					$right = $left = "";
					include(gettpl("medium_title"));
					?>
					<div style="margin-top: 5px;">
						<ul class="menulist">
							<?
							for ($n = 1; $n <= 4; $n++)
							{
							?>
								<li>
									<?
									$e1 = new mObject(ResolvePath("/Root/Etek/Public/Link Library/Courselinks/E$n", "eng"));
									echo cmd(img(geticon($e1->getIcon()))." ".$e1->getName(), "Exec('show','zone_main', Hash('path', '".$e1->getPath()."'))")?>
									<hr style="border: 0; color: #FCE464; background-color: #FCE464; height: 1px; margin: 0;"/>
								</li>
								<?
								$children = fetch("FETCH node WHERE link:node_top='".$e1->getNodeId()."' AND link:type='sub' AND property:class_name='courselink' AND var:lp$period='1' NODESORTBY property:version SORTBY property:name");

								if (count($children) > 0)
								{
								?>
								<li style="padding-left: 16px;">
									<?
									$children = fetch("FETCH node WHERE link:node_top='".$e1->getNodeId()."' AND link:type='sub' AND property:class_name='courselink' AND var:lp$period='1' NODESORTBY property:version SORTBY property:name");

									foreach ($children as $child)
										include(gettpl("small_line", $child));
									?>
								</li>
								<?
								}
							}
							?>
						</ul>
					</div>

				</td>
			</tr>
		</table>
	
		<script type="text/javascript">
		<!--
			var listMenu = new FSMenu('listMenu', true, 'visibility', 'visible', 'hidden');
	
			listMenu.showDelay = 0;
			listMenu.switchDelay = 0;
			listMenu.hideDelay = 500;
			listMenu.cssLitClass = 'highlighted';
	
			var arrow = null;
			if (document.createElement && document.documentElement)
			{
				arrow = document.createElement('span');
				arrow.appendChild(document.createTextNode('>'));
				arrow.className = 'subind';
			}
			listMenu.activateMenu("listMenuRoot", arrow);

			//Load initial ajax-scripts
			Exec('addressbar','zone_addressbar', '');
			Exec('langswitch','zone_language', '');
			Exec('login','zone_login', '');
			Exec('show','zone_main', '');
		// -->
		</script>
<?//PrintPre($_SESSION)?>
	</body>
</html>
