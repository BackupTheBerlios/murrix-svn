<?/*<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">*/?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		
		<title><?=mVar::GetValueByPath("/Root/System/Settings", "sitename")?></title>
		
		<link rel="stylesheet" type="text/css" href="<?=$wwwpath?>/design/stylesheet/style.css"/>
		
		<script type="text/javascript" src="<?=$wwwpath?>/design/javascript/confirmaction.js"></script>
		<script type="text/javascript" src="<?=$wwwpath?>/design/javascript/fold_contact.js"></script>
		<script type="text/javascript" src="<?=$wwwpath?>/design/javascript/alttxt.js"></script>
		<script type="text/javascript" src="<?=$wwwpath?>/design/javascript/dropmenu.js"></script>
		
		<? $_SESSION['murrix']['System']->PrintHeader() ?>
		
		<script type="text/javascript">
			
			function OnLoad()
			{
				SystemRunScript('addressbar','zone_addressbar');
				SystemRunScript('login','zone_login');
				SystemRunScript('menu','zone_menu');
				SystemRunScript('show','zone_main');
				AltxtOnload();
				
				return false;
			}

		</script>
		

	</head>
	<body onLoad="OnLoad()">
		<script type="text/javascript">
			function DropMenu(a, b)
			{
				var menu1=new Array();
				menu1[0]='<a href="http://www.javascriptkit.com">JavaScript Kit</a>';
				menu1[1]='<a href="http://www.freewarejava.com">Freewarejava.com</a>';
				menu1[2]='<a href="http://codingforums.com">Coding Forums</a>';
				menu1[3]='<a href="http://www.cssdrive.com">CSS Drive</a>';
				return dropdownmenu(a, b, menu1, '150px');
			}
		</script>
		<table class="title" cellspacing="0" cellpadding="0">
			<tr>
				<td class="title1" width="80" rowspan="2">
					<img src="<?=$wwwpath?>/design/images/logo64.png"/>
				</td>
				<td class="title1">
					<a class="title2_link" href="<?=mVar::GetValueByPath("/Root/System/Settings", "sitepath")?>"><?=mVar::GetValueByPath("/Root/System/Settings", "sitename")?></a>
				</td>
				<td class="title2" rowspan="2">
					<div id="zone_status"></div>
				</td>
			</tr>
			<tr>
				<td class="title3">
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td class="title1">
								<img src="<?=$wwwpath?>/design/images/menu_right.png"/>
							</td>
							<td class="menu">
							<?
								$root = new mObject($root_id);
								
								$hasmap = array(array("type", array("sub")),
										array("side", "bottom"),
										array("sort", array("name")));
								$menuitems = mObject::GetRelatedWithRights($root->GetRelatedHash($hasmap));
								for ($n = 0; $n < count($menuitems); $n++)
								{
									echo "<a class=\"menubar\" href=\"?path=".$menuitems[$n]->GetPath()."\">";
									echo Img(guiIcon($menuitems[$n]->GetIcon()))." ".ucfirst($menuitems[$n]->name);
									echo "</a>";
								
									if (count($menuitems)-1 > $n)
										echo " &#183; ";
								}
							?>
							</td>
							<td class="title1">
								<img src="<?=$wwwpath?>/design/images/menu_left.png"/>
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

