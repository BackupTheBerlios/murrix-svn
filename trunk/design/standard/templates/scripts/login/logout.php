<form id="sLoginForm" action="javascript:void(null);" onsubmit="Post('login', 'zone_login', 'sLoginForm');">
	<input class="hidden" type="hidden" name="action" value="logout"/>
	<br/>
	
	<?
	if ($_SESSION['murrix']['user']->hasRight("read"))
		echo cmd(img(geticon("user"))." ".$_SESSION['murrix']['user']->getName(), "Exec('show','zone_main',Hash('node_id','".$_SESSION['murrix']['user']->getNodeId()."'))");
	else
		echo img(geticon("user"))." ".$_SESSION['murrix']['user']->getName();
	?>
		
	<br/>
	<input id="sLoginSubmit" class="submit" type="submit" value="<?=ucf(i18n("logout"))?>"/>
</form>
<br/>
<?=cmd(img(geticon("password"))." ".ucf(i18n("change password")), "Exec('admin','zone_main',Hash('show','password_change'))")?>