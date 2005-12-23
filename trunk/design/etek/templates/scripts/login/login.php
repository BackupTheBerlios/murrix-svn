<form id="sLoginForm" action="javascript:void(null);" onsubmit="Post('login', 'zone_login', 'sLoginForm');" style="display:inline">
	<input class="hidden" type="hidden" name="action" value="login"/>
	<?=Img(geticon("user"))?> <?=ucfirst(i18n("username"))?><br/>
	<input class="form2" type="text" size="18" name="username"/><br/>
	<?=Img(geticon("password"))?> <?=ucfirst(i18n("password"))?><br/>
	<input class="form2" type="password" size="18" name="password"/><br/>
	<input id="sLoginSubmit" class="title" type="submit" value="<?=ucfirst(i18n("login"))?>"/>
</form>
