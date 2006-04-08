<?
$current_view = "list";
include(gettpl("scripts/class/adminpanel"));

$left = img(geticon("settings"))."&nbsp;".ucf(i18n("class administration"));
$right = $center = "";
include(gettpl("big_title"));

$classlist = array();
$classlist[] = array(ucf(i18n("name")), "&nbsp;");

$classes = getClassList(true);

foreach ($classes as $class)
{
	$classlist[] = array(cmd(img(geticon($class['default_icon'], 64))."&nbsp;".ucw(str_replace("_", " ", $class['name'])), "Exec('class','zone_main',Hash('show','show','name','".$class['name']."'))"), "&nbsp;");
}

table($classlist, "% ".i18n("rows"));
?>