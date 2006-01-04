<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		
		<link rel="shortcut icon" href="<?=geticon("murrix")?>" type="image/x-icon">
		<title>MURRiX</title>
		
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
					document.getElementById('status').src = '<?=imgpath("loading.gif")?>';
				else
					document.getElementById('status').src = '<?=imgpath("not_loading.gif")?>';
			}
		// -->
		</script>
	</head>

	<body>
		<div id="header">
			<div id="header_wrapper">
				<div id="zone_login"></div>

				<?
					global $root_id;
					$root = new mObject($root_id);
				?>
	
				<div id="header_logo">
					<?=cmd(img(geticon($root->getIcon(), 64)), "Exec('show', 'zone_main', Hash('path', '".$_SESSION['murrix']['default_path']."'))")?>
				</div>
				
				<div id="header_name">
					<?=cmd($root->getVarValue("description"), "Exec('show', 'zone_main', Hash('path', '".$_SESSION['murrix']['default_path']."'))")?>
					<br/>
					<div id="status_holder">
						<img alt="Status indicator" align="middle" src="<?=imgpath("indicator.gif")?>" name="status" id="status" border="0"/>
					</div>
				</div>
			</div>
		</div>

		<div id="bar">
			<div id="zone_addressbar"></div>

			<div id="search">
				<form id="smallSearch" name="smallSearch" action="javascript:void(null);" onsubmit="Post('search', 'zone_main', 'smallSearch')">
					<input id="query" name="query" class="input" type="text" onfocus="if(this.value=='<?=ucf(i18n("enter search here"))?>!')this.value=''" onblur="if(this.value=='')this.value='<?=ucf(i18n("enter search here"))?>!'" value="<?=ucf(i18n("enter search here"))?>!"/>
					<input class="submit" type="submit" value="<?=ucf(i18n("search"))?>"/>
				</form>
			</div>
		</div>

		<div class="clear"></div>
		
		<div id="main">

			<table class="invisible" style="width: 100%" cellspacing="0">
				<tr>
					<td>
					<?
						$menu_id = resolvePath("/Root/Public/Menu");
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
		
		<iframe src="history.php" id="history" name="history" style="width: 0; height: 0; display:none;"></iframe>
		<script type="text/javascript">
		<!--
			//Load initial ajax-scripts
			Exec('addressbar','zone_addressbar', '');
			///Exec('langswitch','zone_language', '');
			Exec('login','zone_login', '');
			Exec('show','zone_main', '');
		// -->
		</script>

	</body>
</html>

