<?
global $root_id;
$root = new mObject($root_id);
//<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
					document.getElementById('status').src = '<?=imgpath("loading.gif")?>';
				else
					document.getElementById('status').src = '<?=imgpath("not_loading.gif")?>';
			}

			function init()
			{
				Exec('addressbar','zone_addressbar', '');
				Exec('login','zone_login', '');
				Exec('show','zone_main', '');
			}
		// -->
		</script>
	</head>

	<body>
		<div id="header">
			<div id="header_wrapper">
				<div id="zone_login"></div>
	
				<div id="header_logo">
					<?=cmd(img(geticon($root->getIcon(), 64)), "Exec('show', 'zone_main', Hash('path', '".$_SESSION['murrix']['default_path']."'))")?>
				</div>
				
				<div id="header_name">
					<?=cmd($root->getVarValue("description"), "Exec('show', 'zone_main', Hash('path', '".$_SESSION['murrix']['default_path']."'))")?>
					<br/>
					<div id="status_holder">
						<img alt="Status indicator" src="<?=imgpath("not_loading.gif")?>" id="status"/>
					</div>
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
		
		<?//<iframe src="history.php" id="history" name="history" style="width: 0; height: 0; display:none;"></iframe>?>
		<script type="text/javascript">
		<!--
			setInterval("Poll()", 100);
		// -->
		</script>

	</body>
</html>

