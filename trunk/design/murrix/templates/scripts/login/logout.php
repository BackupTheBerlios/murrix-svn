<div class="menu_title">
	<?=ucf(i18n("logout"))?>
</div>
<div class="menu_login">
	<div class="label">
		<?=img(geticon("user"))." ".$_SESSION['murrix']['user']->name?>
	</div>
	<form id="sLoginForm" action="javascript:void(null);" onsubmit="Post('login','sLoginForm');">
		<div>
			<input class="hidden" type="hidden" name="action" value="logout"/>
			<input id="sLoginSubmit" class="submit" type="submit" value="<?=ucf(i18n("logout"))?>"/>
		</div>
	</form>
</div>