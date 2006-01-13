<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<META NAME="ROBOTS" CONTENT="NOFOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		
		<link rel="shortcut icon" href="<?=geticon("murrix")?>" type="image/x-icon">
		<title>MURRiX Installer</title>
		
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
				Exec('install','zone_main', '');
			}
		// -->
		</script>
	</head>
	<body onload="OnLoadHandler()">
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
		<div id="header">
			<div id="header_wrapper">
				<div id="header_logo">
					<?=img(geticon("murrix", 64))?>
				</div>
				
				<div id="header_name">
					MURRiX Installer
				</div>
			</div>
		</div>
		<br/>
		
		<center>
			<div style="border: 1px solid #5B5B7A; width: 800px;">
				<div style="align: center; vertical-align: middle;" id="zone_main"></div>
			</div>
		</center>
	</body>
</html>
