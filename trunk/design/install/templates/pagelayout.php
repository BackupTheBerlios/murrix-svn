<?
// Set up default
if ($_SESSION['murrix']['system']->firstrun)
{
	$_SESSION['murrix']['system']->makeActive("install");
	$_SESSION['murrix']['system']->execIntern("exec=install&action=preinstall", "install", array("action"=>"preinstall"));
	
	$_SESSION['murrix']['system']->firstrun = false;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta name="robots" content="nofollow"/>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		
		<link rel="shortcut icon" href="<?=imgpath("favicon.png")?>" type="image/x-icon">
		<title>MURRiX Installer <?=$version?></title>
		
		<?
		$js = getjs();
		for ($i = 0; $i < count($js); $i++)
			echo "<script type=\"text/javascript\" src=\"".$js[$i]."\"></script>\n";

		$js = getcss();
		for ($i = 0; $i < count($js); $i++)
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$js[$i]."\">\n";

		$_SESSION['murrix']['system']->printHeader("exec=install&action=preinstall");
		?>
	</head>
	<body class="body" onload="OnLoadHandler();">
		<center>
		<table class="body_table" cellspacing="0">
			<tr>
				<td colspan="3" class="title">
				</td>
			</tr>
			<tr>
				<td class="fade_left"></td>
				<td class="middle">
					<table class="main_table" cellspacing="0">
						<tr>
							<td class="main">
								<?=$_SESSION['murrix']['system']->createZone("zone_main")?>
							</td>
							<td class="menu">
								<?=$_SESSION['murrix']['system']->createZone("zone_menu")?>
								
								<a href="http://murrix.berlios.de/"><img alt="MURRiX" src="<?=imgpath("murrix.gif")?>" style="width: 80px; height: 15px;"/></a><br/><br/>
								<a href="http://validator.w3.org/check?uri=referer"><img src="<?=imgpath("valid-xhtml10.gif")?>" alt="Valid XHTML 1.1" style="height: 15px; width: 80px;"/></a><br/><br/>
								<a href="http://jigsaw.w3.org/css-validator/check/referer"><img src="<?=imgpath("valid-css.gif")?>" alt="Valid CSS!" style="height: 15px; width: 80px;"/></a><br/><br/>
								<a href="http://www.getfirefox.com/"><img alt="Firefox" src="<?=imgpath("getfirefox.gif")?>" style="width: 80px; height: 15px;"/></a>
							</td>
						</tr>
					</table>
				</td>
				<td class="fade_right"></td>
			</tr>
			<tr>
				<td colspan="3" class="bottom">
					<?=compiletpl("footer", array())?>
				</td>
			</tr>
		</table>
	</center>
	</body>
</html>
