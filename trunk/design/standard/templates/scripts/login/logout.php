<form id="sLoginForm" action="javascript:void(null);" onsubmit="Post('login','sLoginForm');">
	<div>
		<input class="hidden" type="hidden" name="action" value="logout"/>
		<br/>
		<?=img(geticon("user"))." ".$_SESSION['murrix']['user']->name?>
		<br/>
		<input id="sLoginSubmit" class="submit" type="submit" value="<?=ucf(i18n("logout"))?>"/>
	</div>
</form>
<?
if (!empty($_SESSION['murrix']['user']->password))
	echo cmd(img(geticon("password"))." ".ucf(i18n("change password")), "exec=console&initcmd=upass");
?>