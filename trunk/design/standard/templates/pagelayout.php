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
				<td class="title1" width="80">
					<img alt="MURRiX logo" src="<?=imgpath("logo64.png")?>"/>
				</td>
				<td class="title1" width="100%">
					MURRiX
				</td>
				<td class="title2">
					<img alt="Status indicator" align="middle" src="<?=imgpath("indicator.gif")?>" name="status" id="status" border="0"/>
				</td>
				<td class="title2">
					<div id="zone_login"></div>
				</td>
			</tr>
		</table>
		<table class="menu2" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td width="100%">
					<div id="zone_addressbar"></div>
				</td>
				<td align="right">
					<div id="zone_search">
						<form id="smallSearch" name="smallSearch" style="display: inline; margin:0px; padding:0px;" action="javascript:void(null);" onsubmit="Post('search', 'zone_main', 'smallSearch')">
							<table class="invisible_complete" cellspacing="0" cellpadding="0" style="width:100%">
								<tr>
									<td style="padding-left:2px; padding-right:2px">
										<?=img(geticon("search"))?>
									</td>
									<td style="width:100%; padding-right:2px">
										<input id="query" name="query" class="form_search" type="text" onfocus="if(this.value=='<?=ucfirst(i18n("enter search here"))?>!')this.value=''" onblur="if(this.value=='')this.value='<?=ucfirst(i18n("enter search here"))?>!'" value="<?=ucfirst(i18n("enter search here"))?>!"/>
									</td>
									<td>
										<input class="submit_search" type="submit" value="<?=ucfirst(i18n("search"))?>"/>
									</td>
								</tr>
							</table>
						</form>
					</div>
				</td>
			</tr>
		</table>
		<table cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td class="invisible" width="300">
					<div id="zone_menu"></div>
				</td>
				<td class="invisible">
					<div id="zone_main"></div>
				</td>
			</tr>
		</table>

		<a href="http://validator.w3.org/check?uri=referer">
			<img src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Transitional" height="31" width="88">
		</a>
		
		<iframe src="history.php" id="history" name="history" width="0" height="0" style="display:none;"></iframe>

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

