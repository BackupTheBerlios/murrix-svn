<?
global $root_id;
$root = new mObject($root_id);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

	<head>
		<meta name="robots" content="nofollow"/>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
		
		<?
		$rss = new mRSS();
		$feeds = $rss->getFeeds();
		
		foreach ($feeds as $feed)
			echo "<link rel=\"alternate\" type=\"application/rss+xml\" href=\"rssbackend.php?id=".$feed['id']."\" title=\"".$feed['title']."\"/>";
		?>
		
		<link rel="shortcut icon" href="<?=imgpath("favicon.png")?>" type="image/x-icon"/>
		<title><?=$root->getVarValue("description")?></title>
		
		<?
		$js = getjs();
		for ($i = 0; $i < count($js); $i++)
			echo "<script type=\"text/javascript\" src=\"".$js[$i]."\"></script>\n";

		$js = getcss();
		for ($i = 0; $i < count($js); $i++)
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$js[$i]."\"/>\n";

		$_SESSION['murrix']['System']->PrintHeader();
		?>
		<script type="text/javascript">
		<!--
			function loading(state, zone)
			{
				if (state)
				{
					document.getElementById('loadbox').style.display = "block";
				}
				else
				{
					document.getElementById('loadbox').style.display = "none";
				}
			}

			function init()
			{
				Exec('addressbar','zone_addressbar', '');
				Exec('login','zone_login', '');

				return "Exec('show','zone_main', '<?=$_SESSION['murrix']['default_path']?>')";
			}
		// -->
		</script>
	</head>

	<body onload="OnLoadHandler();">
		
		<div id="header">
			<div id="header_wrapper">
				<div style="margin: 5px;" id="zone_login"></div>
				<div style="float: left; width: 310px;">
					<?=img(imgpath("murrix_logo.png"))?>
				</div>
				<div style="float:left; padding-top: 70px; color: #FFFFFF; font-weight: bold; font-size: 14px;">
					<?
					$menuitems = fetch("FETCH node WHERE link:node_top='".getNode("/Root/Public")."' AND link:type='sub' AND !property:class_name='comment' NODESORTBY property:version SORTBY property:name");

					foreach ($menuitems as $child)
					{
						echo "&bull;&nbsp;&nbsp;";
						include(gettpl("small_line", $child));
						echo "&nbsp;&nbsp;";
					}
					?>
					&bull;
				</div>
				
			</div>
		</div>

		<div id="bar">
			<div id="zone_addressbar"></div>

			<form id="smallSearch" action="javascript:void(null);" onsubmit="Post('search', 'zone_main', 'smallSearch')">
				<div id="search">
					<input id="query" name="query" class="input" type="text" onfocus="if(this.value=='<?=ucf(i18n("enter search here"))?>!')this.value=''" onblur="if(this.value=='')this.value='<?=ucf(i18n("enter search here"))?>!'" value="<?=ucf(i18n("enter search here"))?>!"/>
					<input class="submit" type="submit" value="<?=ucf(i18n("search"))?>"/>
				</div>
			</form>
		</div>

		<div class="clear"></div>
		
		<div id="main">

			<div id="content">
				<div id="zone_main"></div>
			</div>
	
			<div id="footer">
				<? include(gettpl("footer")) ?>
			</div>
		</div>
		
		<div id="loadbox">
			<div class="background"></div>
			<div class="main">
				<div class="header">
					<?=ucf(i18n("loading"))."..."?>
				</div>
				<div>
					<?=img(imgpath("loading.gif"))?>
				</div>
			</div>
		</div>
		<div id="popupCalendarDiv" style="visibility:hidden; position:absolute; z-index:11;"></div>
	</body>
</html>

