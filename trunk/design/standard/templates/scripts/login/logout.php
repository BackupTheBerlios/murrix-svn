<?
$home_path = "/Root/Home/".$_SESSION['murrix']['user']->getName();
if (resolvePath($home_path) > 0)
	echo cmd(img(geticon("home"))." ".ucw(i18n("home folder")), "Exec('show', 'zone_main', Hash('path', '$home_path'))");
?>
<br/>
<form id="sLoginForm" action="javascript:void(null);" onsubmit="Post('login', 'zone_login', 'sLoginForm');">
	<input class="hidden" type="hidden" name="action" value="logout"/>
	<br/>
	<?=cmd(img(geticon("user"))." ".$_SESSION['murrix']['user']->getName(), "Exec('show', 'zone_main', Hash('path', '".$_SESSION['murrix']['user']->getPathInTree()."'))")?>
	<br/>
	<input id="sLoginSubmit" class="submit" type="submit" value="<?=ucf(i18n("logout"))?>"/>
</form>