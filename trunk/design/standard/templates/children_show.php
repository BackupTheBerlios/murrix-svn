<?
$invert = $object->getMeta("sort_direction", "") == "asc" ? "!" : "";

$children = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' AND !property:class_name='comment' AND !property:class_name='poll_answer' AND !property:class_name='image_region' NODESORTBY property:version SORTBY $invert".$object->getMeta("sort_by", "property:name"));

$children = getReadable($children);

if (count($children) > 0)
{
	$pagername = "children_show";
	include(gettpl("pager_start", $object));
	
	$view = $object->getMeta("view", "list");
	
	echo compiletpl("scripts/show/children-$view", array("start"=>$start, "end"=>$end, "objects"=>$children), $object);

	include(gettpl("pager_end", $object));
}
?>