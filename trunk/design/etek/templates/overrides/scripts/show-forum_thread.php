<?
global $abspath, $wwwpath;

$view_slected = $object->getMeta("view", "list");

include(gettpl("title_show", $object));

include(gettpl("data_show", $object));

$pagername = "children_show";
$children = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' AND !property:class_name='forum_post' NODESORTBY property:version SORTBY property:name");
include(gettpl("children_show", $object));

$pagername = "forum_thread_show";
$children = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' AND property:class_name='forum_post' NODESORTBY property:version SORTBY property:created");
include(gettpl("forum_thread_show", $object));

?>
