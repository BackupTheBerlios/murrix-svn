<form id="sLoginForm" action="javascript:void(null);" onsubmit="Post('login','sLoginForm')">
	<div>
		<input class="hidden" name="action" value="login" type="hidden"/>
		<?=img(geticon("user"))?> <?=ucf(i18n("username"))?><br/>
		<input class="input" name="username" type="text"/><br/>
		
		<?=img(geticon("password"))?> <?=ucf(i18n("password"))?><br/>
		<input class="input" name="password" type="password"/><br/>
		
		<input id="sLoginSubmit" class="submit" value="<?=ucf(i18n("login"))?>" type="submit"/>
		
	</div>
</form>