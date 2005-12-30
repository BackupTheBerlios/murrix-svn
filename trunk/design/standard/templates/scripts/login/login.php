<form id="sLoginForm" action="javascript:void(null);" onsubmit="Post('login', 'zone_login', 'sLoginForm');">
	<?=Img(geticon("user"))?> <input class="input" name="username" type="text"><br>
	<?=Img(geticon("password"))?> <input class="input" name="password" type="password"><br>
	<input id="sLoginSubmit" class="submit" value="<?=ucfirst(i18n("login"))?>" type="submit">
	<input class="hidden" name="action" value="login" type="hidden">
</form>