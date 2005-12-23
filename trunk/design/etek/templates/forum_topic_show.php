<?
$left = "<span style=\"font-weight: bold;\">".img(geticon("comment"))."&nbsp;".ucfirst(i18n("threads"))."</span>";
$center = $right = "";
if ($object->hasRight("create_subnodes", array("forum_thread")))
{
	$right = cmd(img(geticon("comment"))."&nbsp;".ucfirst(i18n("post new thread")), "Exec('new','zone_main', Hash('path', '".$object->getPath()."', 'class_name', 'forum_thread'))");
}
include(gettpl("medium_title", $object));

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
