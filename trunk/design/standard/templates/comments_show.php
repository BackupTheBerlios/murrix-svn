<?
$left = img(geticon("comment"))."&nbsp;".ucf(i18n("comments"));
$center = $right = "";
if ($object->hasRight("create"))
{
	$right = cmd(img(geticon("comment"))."&nbsp;".ucf(i18n("post")), "exec=new&node_id=".$object->getNodeId()."&class_name=comment");
}
include(gettpl("medium_title", $object));

$pagername = "comments_show";
$children = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' AND property:class_name='comment' NODESORTBY property:version SORTBY property:created");

$children = getReadable($children);

if (count($children) > 0)
{
	include(gettpl("pager_start", $object));

	for ($i = $start; $i < $end; $i++)
	{
		$child = $children[$i];
		include(gettpl("show_line", $child));
	}

	include(gettpl("pager_end", $object));
}
?>
