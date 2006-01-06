<?
echo externcmd(img(geticon("global"))." ".ucf(i18n("external link here")), $_SESSION['murrix']['lastcmd'], "externlink");

$left = img(geticon("settings"))."&nbsp;".ucf(i18n("administration"));
$right = $center = "";
include(gettpl("big_title"));
?>
<div class="main">
	<?=cmd(img(geticon("user"))." ".ucf(i18n("create new user")), "Exec('admin', 'zone_main', Hash('show', 'user_create'))")?>
</div>
