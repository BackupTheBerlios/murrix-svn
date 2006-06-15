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
		<script type="text/javascript" src="3dparty/tiny_mce/tiny_mce_src.js"></script>
		
		<script type="text/javascript">
		<!--
			tinyMCE.init({	theme	: "advanced",
					mode	: "none",
					language : "<?=($_SESSION['murrix']['language'] == "swe" ? "sv" : "en")?>",
					plugins : "iespell,table,insertdatetime,preview,searchreplace,print,contextmenu,paste,directionality",
					theme_advanced_buttons1_add_before : "newdocument,separator",
					theme_advanced_buttons1_add : "fontselect,fontsizeselect",
					theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,separator,forecolor,backcolor,iespell",
					theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
					theme_advanced_buttons3_add_before : "tablecontrols,separator",
					theme_advanced_buttons3_add : "print,separator,ltr,rtl,separator",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					content_css : "/example_data/example_full.css",
					plugin_insertdate_dateFormat : "%Y-%m-%d",
					plugin_insertdate_timeFormat : "%H:%M:%S",
					extended_valid_elements : "hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
					theme_advanced_resize_horizontal : false,
					theme_advanced_resizing : true,
					apply_source_formatting : true,
					spellchecker_languages : "+English=en,Swedish=sv,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es"
					});
					
			function loading(state, zone)
			{
				if (state)
				{
				//	document.getElementById('loadbox').style.display = "block";
				}
				else
				{
				//	document.getElementById('loadbox').style.display = "none";
				}
			}

			function init()
			{
				return "exec=show&path=<?=urlencode($_SESSION['murrix']['default_path'])?>";
			}
		// -->
		</script>
	</head>

	<body onload="OnLoadHandler();">
		
		<div id="header">
			<div id="header_wrapper">
				<div id="zone_login">
				<?
					if (IsAnonymous())
						include(gettpl("scripts/login/login"));
					else
						include(gettpl("scripts/login/logout"));
						
					$_SESSION['murrix']['system']->makeActive("login");
					
				?>
				</div>
	
				<div id="header_logo">
					<?=cmd(img(geticon($root->getIcon(), 64)), "exec=show&path=".$_SESSION['murrix']['default_path'])?>
				</div>
				
				<div id="header_name">
					<?=cmd(getSetting("TITLE", "Welcome to MURRiX"), "exec=show&path=".$_SESSION['murrix']['default_path'])?>
				</div>
			</div>
		</div>

		<div id="bar">
			<div id="zone_addressbar">
			<?
				$path = $_SESSION['murrix']['path'];
				include(gettpl("scripts/addressbar"));
				$_SESSION['murrix']['system']->makeActive("addressbar");
			?>
			</div>

			<form id="smallSearch" action="javascript:void(null);" onsubmit="Post('search','smallSearch')">
				<div id="search">
					<input id="query" name="query" class="input" type="text" onfocus="if(this.value=='<?=ucf(i18n("enter search here"))?>!')this.value=''" onblur="if(this.value=='')this.value='<?=ucf(i18n("enter search here"))?>!'" value="<?=ucf(i18n("enter search here"))?>!"/>
					<input class="submit" type="submit" value="<?=ucf(i18n("search"))?>"/>
				</div>
			</form>
		</div>

		<div class="clear"></div>
		
		<div id="main">

			<table class="content_table" cellspacing="0">
				<tr>
					<td>
					<?
						include(gettpl("menu"));
					?>
					</td>
					<td style="width: 100%">
						<div id="content">
							<div id="zone_main">
							<?
								$object = new mObject(getNode($_SESSION['murrix']['path']));
								include(gettpl("scripts/show", $object));
								$_SESSION['murrix']['system']->makeActive("show");
							?>
							</div>
						</div>
					</td>
				</tr>
			</table>

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

