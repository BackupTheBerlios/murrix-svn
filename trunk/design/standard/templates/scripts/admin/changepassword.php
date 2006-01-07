<?
echo externcmd(img(geticon("global"))." ".ucf(i18n("external link here")), $_SESSION['murrix']['lastcmd'], "externlink");

$current_view = "password_change";
include(gettpl("scripts/admin/adminpanel"));

$left = img(geticon("settings"))."&nbsp;".ucf(i18n("administration")." - ".ucf(i18n("change passord")));
$right = $center = "";
include(gettpl("big_title"));

?>
<form name="sPasswordChange" id="sPasswordChange" action="javascript:void(null);" onsubmit="Post('admin', 'zone_main', 'sPasswordChange');">
	<input class="hidden" type="hidden" name="action" value="changepassword"/>
	<div class="main">
		<?=ucf(i18n("password"))?>:
		<br/>
		<input class="input" type="password" name="password1"/> <input class="input" type="password" name="password2"/>
		<br/>
		<br/>
		<input class="submit" id="submitButton" type="submit" value="<?=ucf(i18n("save"))?>"/>
	</div>
</form>

