<?
echo externcmd(img(geticon("global"))." ".ucf(i18n("external link here")), $_SESSION['murrix']['lastcmd'], "externlink");

$current_view = "group_create";
include(gettpl("scripts/admin/adminpanel"));

$left = img(geticon("settings"))."&nbsp;".ucf(i18n("administration")." - ".ucf(i18n("create new group")));
$right = $center = "";
include(gettpl("big_title"));

?>
<form name="sGroupCreate" id="sGroupCreate" action="javascript:void(null);" onsubmit="Post('admin', 'zone_main', 'sGroupCreate');">
	<input class="hidden" type="hidden" name="action" value="creategroup"/>
	<div class="main">
		<?=ucf(i18n("name"))?>:
		<br/>
		<input class="input" type="text" name="name"/>
		<br/>
		<br/>
		<?=ucf(i18n("description"))?>:
		<br/>
		<textarea class="input" name="description"></textarea>
		<br/>
		<br/>
		<input class="submit" id="submitButton" type="submit" value="<?=ucf(i18n("create"))?>"/>
	</div>
</form>

