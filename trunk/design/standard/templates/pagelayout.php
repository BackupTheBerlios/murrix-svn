<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		
		<title><?//=mVar::GetValueByPath("/Root/System/Settings", "sitename")?></title>
		
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
			
			function OnLoad()
			{
				//SystemRunScript('addressbar','zone_addressbar', '');
				//SystemRunScript('login','zone_login', '');
				SystemRunScript('show','zone_main', '');
				SystemRunScript('menu','zone_menu', '');
				AltxtOnload();
				return false;
			}

		</script>

	</head>
	<body onLoad="OnLoad()">
		<table class="title" cellspacing="0" cellpadding="0">
			<tr>
				<td class="title1" width="80" rowspan="2">
					<img src="<?=imgpath("logo64.png")?>"/>
				</td>
				<td class="title1">
					<a class="title2_link" href="<?//=mVar::GetValueByPath("/Root/System/Settings", "sitepath")?>"><?//=mVar::GetValueByPath("/Root/System/Settings", "sitename")?></a>
				</td>
				<td class="title2" rowspan="2">
					<img align="middle" src="" name="status" id="status" border="0"/>
					<div id="zone_status"></div>
				</td>
			</tr>
			<tr>
				<td class="title3">
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td class="title1">
								<img src="<?=imgpath("menu_right.png")?>"/>
							</td>
							<td class="menu">
							<?
								/*$root = new mObject($root_id);
								
								$hasmap = array(array("type", array("sub")),
										array("side", "bottom"),
										array("sort", array("name")));
								$menuitems = mObject::GetRelatedWithRights($root->GetRelatedHash($hasmap));
								for ($n = 0; $n < count($menuitems); $n++)
								{
									echo "<a class=\"menubar\" onclick=\"SystemRunScript('show','zone_main', Hash('path', '".$menuitems[$n]->GetPath()."'));\" href=\"javascript:void(null);\">";
									echo Img(geticon($menuitems[$n]->GetIcon()))." ".ucfirst($menuitems[$n]->name);
									echo "</a>";
								
									if (count($menuitems)-1 > $n)
										echo " &#183; ";
								}*/
							?>
							</td>
							<td class="title1">
								<img src="<?=imgpath("menu_left.png")?>"/>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<table class="menu2" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td>
					<div id="zone_addressbar"></div>
				</td>
				<td align="right">
					<div id="zone_login"></div>
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
		
		<div id="navtxt" class="navtext" style="visibility:hidden; position:absolute; top:0px; left:-400px; z-index:10000; padding:5px"></div>

	</body>
</html>

