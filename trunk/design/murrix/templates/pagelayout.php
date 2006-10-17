<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

	<head>
		<meta name="robots" content="nofollow"/>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
		
		<?
		$xml = new mXml();
		$feeds = $xml->getFeeds();
		
		foreach ($feeds as $feed)
			echo "<link rel=\"alternate\" type=\"application/rss+xml\" href=\"?rss&id=".$feed['id']."\" title=\"".$feed['title']."\"/>";
		?>
		
		<link rel="shortcut icon" href="<?=imgpath("favicon.png")?>" type="image/x-icon"/>
		<title>MURRiX</title>
		
		<?
		$js = getjs();
		for ($i = 0; $i < count($js); $i++)
			echo "<script type=\"text/javascript\" src=\"".$js[$i]."\"></script>\n";

		$js = getcss();
		for ($i = 0; $i < count($js); $i++)
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$js[$i]."\"/>\n";

		$_SESSION['murrix']['system']->PrintHeader();
		?>
		<script type="text/javascript">
		<!--
			function init()
			{
				return "exec=show&path=<?=urlencode($_SESSION['murrix']['default_path'])?>";
			}
			
			var myrules = {
				'a.cmd' : function(element) {
					var parts = element.href.split("?");
					
					if (typeof parts[1] != 'undefined')
						element.href = "javascript:setRun('"+parts[1]+"')";
				}
			};
			
			Behaviour.register(myrules);
		// -->
		</script>
	</head>

	<body class="body" onload="OnLoadHandler();Behaviour.apply()">
	<center>
		<table class="body_table" cellspacing="0">
			<tr>
				<td colspan="3" class="title">
					<div id="zone_language">
					<?
						echo compiletpl("scripts/langswitch", array());
						$_SESSION['murrix']['system']->makeActive("langswitch");
					?>
					</div>
				</td>
			</tr>
			<tr>
				<td class="fade_left"></td>
				<td class="middle">
					<table class="main_table" cellspacing="0">
						<tr>
							<td class="main">
								<?=compiletpl("buttons", array())?>
								<div id="zone_addressbar">
								<?
									echo compiletpl("scripts/addressbar", array("divider"=>"&gt;", "path"=>$_SESSION['murrix']['path']));
									$_SESSION['murrix']['system']->makeActive("addressbar", array("divider"=>"&gt;"));
								?>
								</div>
								<div id="zone_main">
								<?
									$object = new mObject(getNode($_SESSION['murrix']['path']));
									if ($object->HasRight("read"))
										echo compiletpl("scripts/show/view", array(), $object);
									else
										echo compiletpl("message", array("title"=>ucf(i18n("error")), "message"=>ucf(i18n("not enough rights"))));
									$_SESSION['murrix']['system']->makeActive("show");
								?>
								</div>
							</td>
							<td class="menu">
								<?=compiletpl("menu_search", array())?>
								<div id="zone_login">
								<?
									if (IsAnonymous())
										echo compiletpl("scripts/login/login", array());
									else
										echo compiletpl("scripts/login/logout", array());
										
									$_SESSION['murrix']['system']->makeActive("login");
								?>
								</div>
								
								<div class="menu_title">
								<?
									$timestamp = strtotime(date("Y-m")."-01");
									echo cmd(ucf(i18n("calendar")), "exec=calendar&view=month&date=".date("Ymd", $timestamp), "link");
								?>
								</div>
								<?=ucf(i18n(strtolower(date("F", $timestamp))))."&nbsp;".date("Y", $timestamp)?>
								<div class="menu_calendar">
									<?=compiletpl("scripts/calendar/small_month_inner", array("firstday"=>$timestamp))?>
									
								</div>
								
								<a href="http://murrix.berlios.de/"><img alt="MURRiX" src="<?=imgpath("murrix.gif")?>" style="width: 80px; height: 15px;"/></a><br/><br/>
								<a href="http://validator.w3.org/check?uri=referer"><img src="<?=imgpath("valid-xhtml10.gif")?>" alt="Valid XHTML 1.1" style="height: 15px; width: 80px;"/></a><br/><br/>
								<a href="http://jigsaw.w3.org/css-validator/check/referer"><img src="<?=imgpath("valid-css.gif")?>" alt="Valid CSS!" style="height: 15px; width: 80px;"/></a><br/><br/>
								<a href="http://www.getfirefox.com/"><img alt="Firefox" src="<?=imgpath("getfirefox.gif")?>" style="width: 80px; height: 15px;"/></a>
							</td>
						</tr>
					</table>
				</td>
				<td class="fade_right"></td>
			</tr>
			<tr>
				<td colspan="3" class="bottom">
					<?=compiletpl("footer", array())?>
				</td>
			</tr>
		</table>
	</center>
	</body>
</html>

