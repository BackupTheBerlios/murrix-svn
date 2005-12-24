<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="shortcut icon" href="<?=geticon("murrix", 16, "ico")?>" type="image/x-icon">
		<title>MURRiX Install</title>
		
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
					document.getElementById('status').src = '<?=imgpath("indicator.gif")?>';
				else
					document.getElementById('status').src = '<?=imgpath("")?>';
			}
		// -->
		</script>
	</head>
	<body>
		<table class="title" cellspacing="0" cellpadding="0">
			<tr>
				<td class="title1" width="80" rowspan="2">
					<img src="<?=imgpath("logo64.png")?>"/>
				</td>
				<td class="title1">
					MURRiX Installer
				</td>
				<td class="title2" rowspan="2">
					<img align="middle" src="" name="status" id="status" border="0"/>
				</td>
			</tr>
		</table>

		<br/>
		
		<center>
			<div style="border: 1px solid #5B5B7A; width: 800px;">
				<div style="align: center; vertical-align: middle;" id="zone_main"></div>
			</div>
		<center>
		
		<iframe src="history.php" id="history" name="history" width="0" height="0" style="display:none;"></iframe>

		<script type="text/javascript">
		<!--
			Exec('install', 'zone_main', '');
		// -->
		</script>
	</body>
</html>
