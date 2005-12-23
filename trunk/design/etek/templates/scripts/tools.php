<?
$current_view = "tools";
include(gettpl("adminpanel", $object));

$right = $center = "";
$left = "<span style=\"font-weight: bold; font-size: 18px;\">".img(geticon("settings"))."&nbsp;".ucf(i18n("tools"))."</span>";
include(gettpl("big_title", $object));


$left = "<span style=\"font-weight: bold;\">".ucf(i18n("parentnodes"))."</span>";
$center = $right = "";
include(gettpl("medium_title", $object));
?>
<div id="main" style="margin-top: 5px">
<?
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

	guiList($list, "% ".i18n("rows"));
?>
</div>
<?
$left = "<span style=\"font-weight: bold;\">".ucf(i18n("subobjekt"))."</span>";
$center = $right = "";
include(gettpl("medium_title", $object));
?>
<div id="main" style="margin-top: 5px">
<?
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

	guiList($list, "% ".i18n("rows"));
?>
</div>
