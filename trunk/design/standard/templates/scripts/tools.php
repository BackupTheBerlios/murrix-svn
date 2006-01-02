<?
echo externcmd(img(geticon("global"))." ".ucf(i18n("external link here")), $_SESSION['murrix']['lastcmd'], "externlink");

$current_view = "tools";
include(gettpl("adminpanel", $object));

$right = $center = "";
$left = img(geticon("settings"))."&nbsp;".ucf(i18n("tools"));
include(gettpl("big_title", $object));


$left = ucf(i18n("parentnodes"));
$center = $right = "";
include(gettpl("medium_title", $object));

$parents = fetch("FETCH node WHERE link:node_bottom='".$object->getNodeId()."' AND link:type='sub' NODESORTBY property:version SORTBY property:name");
$list = array();
$list[] = array(ucf(i18n("name")), ucf(i18n("class")));

foreach ($parents as $parent)
{
	if ($parent->getCreator() == 0)
		$creator = ucf(i18n("unknown"));
	else
	{
		$creator_obj = new mObject($parent->getCreator());
		$creator = cmd($creator_obj->getName(), "SystemRunScript('show','zone_main', Hash('path', '".$creator_obj->getPath()."'))");
	}

	$list[] = array($parent->getName(),  $parent->getClassName());
}

table($list, "% ".i18n("rows"));

$left = ucf(i18n("subnodes"));
$center = $right = "";
include(gettpl("medium_title", $object));

$children = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' NODESORTBY property:version SORTBY property:name");
$list = array();
$list[] = array(ucf(i18n("name")), ucf(i18n("class")));

foreach ($children as $child)
{
	if ($child->getCreator() == 0)
		$creator = ucf(i18n("unknown"));
	else
	{
		$creator_obj = new mObject($child->getCreator());
		$creator = cmd($creator_obj->getName(), "SystemRunScript('show','zone_main', Hash('path', '".$creator_obj->getPath()."'))");
	}

	$list[] = array($child->getName(),  $child->getClassName());
}

table($list, "% ".i18n("rows"));
?>

