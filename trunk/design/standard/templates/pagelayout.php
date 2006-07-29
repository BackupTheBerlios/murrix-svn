<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
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
				var arrowObj = document.getElementById(itemName+'_arrow');
				
				if (containerObj.style.display == 'none') // Show container
				{
					containerObj.style.display = 'block';
					arrowObj.src = '<?=imgpath("1downarrow.png")?>';
				}
				else // Hide container
				{
					containerObj.style.display = 'none';
					arrowObj.src = '<?=imgpath("1uparrow.png")?>';
				}
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
		<div style="float: right; padding: 7px;" id="zone_language">
		<?
			echo compiletpl("scripts/langswitch", array());
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
				echo compiletpl("scripts/addressbar", array("path"=>$_SESSION['murrix']['path']));
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
						echo compiletpl("menu", array());
						$_SESSION['murrix']['system']->makeActive(	"zone", 
												array("zone_menu" => array(	
															"template" => "menu",
															"events" => array(
																	"login",
																	"logout",
																	"newlang"))));
					?>
					</div>
				</td>
				<td class="middle">
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
				<td class="sidebar">
					<div class="title">
						<a class="right" href="javascript:void(null)" onclick="toggleSidebarContainer('login')"><?=img(imgpath("1downarrow.png"), "", "", "login_arrow")?></a>
						<?=ucf(i18n("login"))?>
					</div>
					<div id="login_container" class="container">
						<div id="zone_login">
						<?
							if (IsAnonymous())
								echo compiletpl("scripts/login/login", array());
							else
								echo compiletpl("scripts/login/logout", array());
								
							$_SESSION['murrix']['system']->makeActive("login");
						?>
						</div>
					</div>
					
					<div class="title">
						<a class="right" href="javascript:void(null)" onclick="toggleSidebarContainer('calendar')"><?=img(imgpath("1downarrow.png"), "", "", "calendar_arrow")?></a>
						<?=cmd(ucf(i18n("calendar")), "exec=calendar", "sidebar")?>
					</div>
					<div id="calendar_container" class="container">
						<div class="container">
							<?=compiletpl("scripts/calendar/small_month", array("firstday"=>strtotime(date("Y-m")."-01")))?>
						</div>
					</div>
					
					<div id="zone_poll">
					<?
						echo compiletpl("scripts/poll/view", array());
						$_SESSION['murrix']['system']->makeActive("poll");
					?>
					</div>
					
					<div id="zone_info">
					<?
						echo compiletpl("info", array());
						$_SESSION['murrix']['system']->makeActive(	"zone", 
												array("zone_info" => array(	
															"template" => "info",
															"events" => "poll")));
					?>
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