<?
echo externcmd(img(geticon("global"))." ".ucf(i18n("external link here")), $_SESSION['murrix']['lastcmd'], "externlink");

$current_view = "user_create";
include(gettpl("scripts/admin/adminpanel"));

$left = img(geticon("settings"))."&nbsp;".ucf(i18n("administration")." - ".ucf(i18n("create new user")));
$right = $center = "";
include(gettpl("big_title"));

?>
<form name="sUserCreate" id="sUserCreate" action="javascript:void(null);" onsubmit="Post('admin', 'zone_main', 'sUserCreate');">
	<input class="hidden" type="hidden" name="action" value="createuser"/>
	<div class="main">
		<?=ucf(i18n("name"))?>:
		<br/>
		<input class="input" type="text" name="name"/>
		<br/>
		<br/>
		<?=ucf(i18n("username"))?>:
		<br/>
		<input class="input" type="text" name="username"/>
		<br/>
		<br/>
		<?=ucf(i18n("password"))?>:
		<br/>
		<input class="input" type="password" name="password1"/> <input class="input" type="password" name="password2"/>
		<br/>
		<br/>
		<input class="submit" id="submitButton" type="submit" value="<?=ucf(i18n("create"))?>"/>
	</div>
</form>

