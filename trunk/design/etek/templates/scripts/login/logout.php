<form id="sLoginForm" action="javascript:void(null);" onsubmit="Post('login', 'zone_login', 'sLoginForm');" style="display:inline">
	<input class="hidden" type="hidden" name="action" value="logout"/>
	<br/>
	<?=cmd(img(geticon("user"))." ".$_SESSION['murrix']['user']->getName(), "Exec('show', 'zone_main', Hash('path', '".$_SESSION['murrix']['user']->getPath()."'))")?>
	<br/>
	<br/>
	<input id="sLoginSubmit" class="title" type="submit" value="<?=ucfirst(i18n("logout"))?>"/>
</form>