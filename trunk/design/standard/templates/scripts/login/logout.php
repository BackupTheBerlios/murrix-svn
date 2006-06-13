<form id="sLoginForm" action="javascript:void(null);" onsubmit="Post('login', 'zone_login', 'sLoginForm');">
	<div>
		<input class="hidden" type="hidden" name="action" value="logout"/>
		<br/>
		
		<?
			echo img(geticon("user"))." ".$_SESSION['murrix']['user']->name;
		?>
			
		<br/>
		<input id="sLoginSubmit" class="submit" type="submit" value="<?=ucf(i18n("logout"))?>"/>
	</div>
</form>
<?=cmd(img(geticon("password"))." ".ucf(i18n("change password")), "Exec('console','zone_main',Hash('initcmd','passwd'))")?>