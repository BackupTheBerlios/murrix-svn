<div class="container">
<?
//if (!empty($_SESSION['murrix']['user']->password))
//	echo cmd(img(geticon("password"))." ".ucf(i18n("change password")), "exec=console&initcmd=upass");
	echo img(geticon("user"))." ".$_SESSION['murrix']['user']->name;
?>
</div>
<form id="sLoginForm" action="javascript:void(null);" onsubmit="Post('login','sLoginForm');">
	<div>
		<input class="hidden" type="hidden" name="action" value="logout"/>
		<input id="sLoginSubmit" class="submit" type="submit" value="<?=ucf(i18n("logout"))?>"/>
	</div>
</form>
