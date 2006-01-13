<?
global $abspath, $wwwpath;

$view_slected = $object->getMeta("view", "list");

include(gettpl("title_show", $object));

include(gettpl("data_show", $object));

$pagername = "children_show";
$children = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' AND !property:class_name='comment' NODESORTBY property:version SORTBY property:name");

$list_sub = $object->hasRight("list_sub");

if (!$list_sub)
	$children = getReadable($children);

include(gettpl("children_show", $object));

$pagername = "comments_show";
$children = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' AND property:class_name='comment' NODESORTBY property:version SORTBY property:created");

if (!$list_sub)
	$children = getReadable($children);

include(gettpl("comments_show", $object));
?>
