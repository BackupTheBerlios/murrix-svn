<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

	<head>
		<meta name="robots" content="nofollow"/>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
		
		<?
		$rss = new mRSS();
		$feeds = $rss->getFeeds();
		
		foreach ($feeds as $feed)
			echo "<link rel=\"alternate\" type=\"application/rss+xml\" href=\"$wwwpath/backends/rss.php?id=".$feed['id']."\" title=\"".$feed['title']."\"/>";
		?>
		
		<link rel="shortcut icon" href="<?=geticon("murrix")?>" type="image/x-icon"/>
		<title><?=getSetting("TITLE", "Welcome to MURRiX")?></title>
		
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
			
			function toggleSidebarContainer(itemName)
			{
				var containerObj = document.getElementById(itemName+'_container');
				var rightObj = document.getElementById(itemName+'_right');
				var leftObj = document.getElementById(itemName+'_left');
				
				if (containerObj.style.display == 'none') // Show container
				{
					containerObj.style.display = 'block';
					leftObj.innerHTML = '&uarr;&uarr;';
					rightObj.innerHTML = '&uarr;&uarr;';
				}
				else // Hide container
				{
					containerObj.style.display = 'none';
					leftObj.innerHTML = '&darr;&darr;';
					rightObj.innerHTML = '&darr;&darr;';
				}
			}
		// -->
		</script>
	</head>

	<body class="body" onload="OnLoadHandler();">
		<div style="float: right; padding: 7px;" id="zone_language">
		<?
			include(gettpl("scripts/langswitch"));
			$_SESSION['murrix']['system']->makeActive("langswitch");
		?>
		</div>
		
		<div class="header">
			<?=getSetting("TITLE", "Welcome to MURRiX")?>
		</div>
		
		<div class="bar">
			<div class="search">
				<form id="smallSearch" action="javascript:void(null);" onsubmit="Post('search','smallSearch')">
					<div>
						<input class="input" id="query" name="query" type="text" onfocus="if(this.value=='<?=ucf(i18n("enter search here"))?>!')this.value=''" onblur="if(this.value=='')this.value='<?=ucf(i18n("enter search here"))?>!'" value="<?=ucf(i18n("enter search here"))?>!"/>
						<input class="search" type="image" name="submit" src="<?=geticon("search")?>" alt="<?=ucf(i18n("search"))?>"/>
					</div>
				</form>
			</div>
			
			<div class="address" id="zone_addressbar">
			<?
				$path = $_SESSION['murrix']['path'];
				include(gettpl("scripts/addressbar"));
				$_SESSION['murrix']['system']->makeActive("addressbar");
			?>
			</div>
			
			<div class="clear"></div>
		</div>
		
		<table class="maintable" cellspacing="0">
			<tr class="row">
				<td class="sidebar">
					<div id="zone_menu">
					<?
						include(gettpl("menu"));
						$_SESSION['murrix']['system']->makeActive("zone", array("zone_menu" => "menu"));
					?>
					</div>
				</td>
				<td class="middle">
					<div id="zone_main">
					<?
						$object = new mObject(getNode($_SESSION['murrix']['path']));
						if ($object->HasRight("read"))
						{
							include(gettpl("scripts/show", $object));
						}
						else
						{
							$titel = ucf(i18n("error"));
							$text = ucf(i18n("not enough rights"));
							include(gettpl("message"));
						}
						$_SESSION['murrix']['system']->makeActive("show");
					?>
					</div>
				</td>
				<td class="sidebar">
					<div class="title">
						<a class="right" id="login_right" href="javascript:void(null)" onclick="toggleSidebarContainer('login')">&uarr;&uarr;</a>
						<a class="left" id="login_left" href="javascript:void(null)" onclick="toggleSidebarContainer('login')">&uarr;&uarr;</a>
						<?=ucf(i18n("login"))?>
					</div>
					<div id="login_container" class="container">
						<div id="zone_login">
						<?
							if (IsAnonymous())
								include(gettpl("scripts/login/login"));
							else
								include(gettpl("scripts/login/logout"));
								
							$_SESSION['murrix']['system']->makeActive("login");
							?>
						</div>
					</div>
					
					<div class="title">
						<a class="right" id="calendar_right" href="javascript:void(null)" onclick="toggleSidebarContainer('calendar')">&uarr;&uarr;</a>
						<a class="left" id="calendar_left" href="javascript:void(null)" onclick="toggleSidebarContainer('calendar')">&uarr;&uarr;</a>
						<?=cmd(ucf(i18n("calendar")), "exec=calendar", "sidebar")?>
					</div>
					<div id="calendar_container" class="container">
						<div class="container">
						<?
							$firstday = strtotime(date("Y-m")."-01");
							include(gettpl("scripts/calendar/small_month"));
						?>
						</div>
					</div>
					
					<div class="title">
						<a class="right" id="polls_right" href="javascript:void(null)" onclick="toggleSidebarContainer('polls')">&uarr;&uarr;</a>
						<a class="left" id="polls_left" href="javascript:void(null)" onclick="toggleSidebarContainer('polls')">&uarr;&uarr;</a>
						<?=ucf(i18n("polls"))?>
					</div>
					<div id="polls_container" class="container">
						<div id="zone_poll">
						<?
							include(gettpl("scripts/poll/view"));
							$_SESSION['murrix']['system']->makeActive("poll");
						?>
						</div>
					</div>
				</td>
			</tr>
		</table>
		
		<div class="footer">
			<? include(gettpl("footer")) ?>
		</div>

		<div id="popupCalendarDiv" style="visibility:hidden; position:absolute; z-index:11;"></div>
	</body>
</html>

