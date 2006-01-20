<?
global $root_id;
$root = new mObject($root_id);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

	<head>
		<meta name="robots" content="nofollow"/>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
		
		<link rel="shortcut icon" href="<?=geticon($root->getIcon())?>" type="image/x-icon"/>
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
			function loading(state)
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
				<div id="zone_login"></div>
	
				<div id="header_logo">
					<?=cmd(img(geticon($root->getIcon(), 64)), "Exec('show', 'zone_main', Hash('path', '".$_SESSION['murrix']['default_path']."'))")?>
				</div>
				
				<div id="header_name">
					<?=cmd($root->getVarValue("description"), "Exec('show', 'zone_main', Hash('path', '".$_SESSION['murrix']['default_path']."'))")?>
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

			<table class="content_table" cellspacing="0">
				<tr>
					<td>
					<?
						include(gettpl("menu"));
					?>
					</td>
					<td style="width: 100%">
						<div id="content">
							<div id="zone_main"></div>
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

