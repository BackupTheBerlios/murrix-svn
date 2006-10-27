<?
// Set up default
if ($_SESSION['murrix']['system']->firstrun)
{
	$_SESSION['murrix']['system']->makeActive("langswitch");
	$_SESSION['murrix']['system']->execIntern("exec=langswitch", "langswitch");
	
	$_SESSION['murrix']['system']->makeActive("addressbar", array("divider"=>"&gt;"));
	$_SESSION['murrix']['system']->execIntern("exec=addressbar", "addressbar");

	if (empty($_SESSION['murrix']['system']->command))
	{
		$_SESSION['murrix']['system']->makeActive("show");
		$_SESSION['murrix']['system']->execIntern("exec=show", "show");
	}
	
	$_SESSION['murrix']['system']->makeActive("login");
	$_SESSION['murrix']['system']->execIntern("exec=login", "login");
	
	$_SESSION['murrix']['system']->makeActive("poll");
	$_SESSION['murrix']['system']->execIntern("exec=poll", "poll");
	
	$_SESSION['murrix']['system']->makeActive(	"zone", 
							array("zone_info" => array(	
										"template" => "info",
										"events" => array(
												"poll",
												"login",
												"logout",
												"newlang"))));
	$_SESSION['murrix']['system']->makeActive(	"zone", 
							array("zone_menu" => array(	
										"template" => "menu",
										"events" => array(
												"login",
												"logout",
												"newlang"))));
	$_SESSION['murrix']['system']->execIntern("exec=zone", "zone");
	$_SESSION['murrix']['system']->triggerEventIntern("poll");
	
	$_SESSION['murrix']['system']->firstrun = false;
}

?>

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
		// -->
		</script>
	</head>

	<body class="body" onload="OnLoadHandler();">
		<div style="float: right; padding: 7px;">
			<?=$_SESSION['murrix']['system']->createZone("zone_language")?>
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
			
			<div class="address">
				<?=$_SESSION['murrix']['system']->createZone("zone_addressbar")?>
			</div>
			
			<div class="clear"></div>
		</div>
		
		<table class="maintable" cellspacing="0">
			<tr class="row">
				<td class="sidebar">
					<?=$_SESSION['murrix']['system']->createZone("zone_menu")?>
				</td>
				<td class="middle">
					<?=$_SESSION['murrix']['system']->createZone("zone_main")?>
				</td>
				<td class="sidebar">
					<div class="title">
						<a class="right" href="javascript:void(null)" onclick="toggleSidebarContainer('login')"><?=img(imgpath("1downarrow.png"), "", "", "login_arrow")?></a>
						<?=ucf(i18n("login"))?>
					</div>
					<div id="login_container" class="container">
						<?=$_SESSION['murrix']['system']->createZone("zone_login")?>
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
					
					<?=$_SESSION['murrix']['system']->createZone("zone_poll")?>
					
					<?=$_SESSION['murrix']['system']->createZone("zone_info")?>
				</td>
			</tr>
		</table>
		
		<div class="footer">
			<? include(gettpl("footer")) ?>
		</div>
		
		<div id="popupCalendarDiv" style="visibility:hidden; position:absolute; z-index:11;"></div>
	</body>
</html>