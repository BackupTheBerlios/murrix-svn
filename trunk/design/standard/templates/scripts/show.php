<?

global $abspath, $wwwpath;

$view_slected = $object->getMeta("view", "list");

include(gettpl("title_show", $object));
	
//$cachename = "show_".$object->getNodeId();
//registerFileCache($cachename, "+1 year");
//$node_id_list = array($object->getNodeId());

//$buffer = getFileCache($cachename);
//if ($buffer === false)
{
	//startFileCache($cachename);
	include(gettpl("data_show", $object));
	
	$invert = "";
	if ($object->getMeta("sort_direction", "") == "asc")
		$invert = "!";
	
	$pagername = "children_show";
	$children = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' AND !property:class_name='comment' AND !property:class_name='poll_answer' NODESORTBY property:version SORTBY $invert".$object->getMeta("sort_by", "property:name"));
	
	//foreach ($children as $child)
	//	$node_id_list[] = $child->getNodeId();
	
	$children = getReadable($children);
	
	include(gettpl("children_show", $object));

	//$buffer = stopFileCache($cachename, $node_id_list);
}
//echo $buffer;

if ($object->getMeta("show_comments", 0) == 1)
{
	$pagername = "comments_show";
	$children = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' AND property:class_name='comment' NODESORTBY property:version SORTBY property:created");
	
	//foreach ($children as $child)
	//	$node_id_list[] = $child->getNodeId();
	
	$children = getReadable($children);
	
	include(gettpl("comments_show", $object));
}

?>