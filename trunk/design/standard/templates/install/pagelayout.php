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

		$_SESSION['murrix']['system']->PrintHeader();
		?>
		<script type="text/javascript">
		<!--
			function loading(state)
			{
				if (state)
				{
					//document.getElementById('loadbox').style.display = "block";
				}
				else
				{
					//document.getElementById('loadbox').style.display = "none";
				}
			}
			
			function init()
			{
				return "exec=install";
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
	<body onload="OnLoadHandler();Behaviour.apply()">
		<div id="install">
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
				<div class="main_wrapper">
					<div id="zone_main">
					<?
						echo compiletpl("install/stage1", array());
						$_SESSION['murrix']['system']->makeActive("install");
					?>
					</div>
				</div>
			</center>
		</div>
	</body>
</html>
