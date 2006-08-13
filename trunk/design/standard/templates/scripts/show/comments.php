<?
$args_title = array();
$args_title['left'] = img(geticon("comment"))."&nbsp;".ucf(i18n("comments"));
if ($object->hasRight("create") || $object->hasRight("comment"))
	$args_title['right'] = cmd(img(geticon("comment"))."&nbsp;".ucf(i18n("post")), "exec=new&node_id=".$object->getNodeId()."&class_name=comment");

echo compiletpl("title/medium", $args_title, $object);

$pagername = "comments_show";
$children = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' AND property:class_name='comment' NODESORTBY property:version SORTBY property:created");

$children = getReadable($children);

if (count($children) > 0)
{
	include(gettpl("pager/start", $object));

	for ($i = $start; $i < $end; $i++)
		echo compiletpl("scripts/show/line", array(), $children[$i]);

	include(gettpl("pager/end", $object));
}
?>
