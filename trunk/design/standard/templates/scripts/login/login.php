<form id="sLoginForm" action="javascript:void(null);" onsubmit="Post('login', 'zone_login', 'sLoginForm');">
	<?=img(geticon("user"))?> <input class="input" name="username" type="text"><br>
	<?=img(geticon("password"))?> <input class="input" name="password" type="password"><br>
	<input id="sLoginSubmit" class="submit" value="<?=ucf(i18n("login"))?>" type="submit">
	<input class="hidden" name="action" value="login" type="hidden">
</form>