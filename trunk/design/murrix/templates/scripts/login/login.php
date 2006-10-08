<div class="menu_title">
	<?=ucf(i18n("login"))?>
</div>
<div class="menu_login">
	<form id="sLoginForm" action="javascript:void(null);" onsubmit="Post('login','sLoginForm')">
		<div>
			<input class="hidden" name="action" value="login" type="hidden"/>
			
			<div class="label">
				<?=img(geticon("user"))?> <?=ucf(i18n("username"))?>
			</div>
			
			<input class="input" name="username" type="text"/>
			
			<div class="label">
				<?=img(geticon("password"))?> <?=ucf(i18n("password"))?>
			</div>
			
			<input class="input" name="password" type="password"/><br/>
			
			<input id="sLoginSubmit" class="submit" value="<?=ucf(i18n("login"))?>" type="submit"/>
		</div>
	</form>
</div>