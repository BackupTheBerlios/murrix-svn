<?

$left = "<span style=\"font-weight: bold;\">".img(geticon("comment"))."&nbsp;".ucf(i18n("comments"))."</span>";
$center = $right = "";
if ($object->hasRight("create_subnodes", array("comment")))
{
	$right = cmd(img(geticon("comment"))."&nbsp;".ucf(i18n("post")), "Exec('new','zone_main', Hash('path', '".$object->getPath()."', 'class_name', 'comment'))");
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