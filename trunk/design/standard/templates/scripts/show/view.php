<?
echo compiletpl("scripts/show/title", array(), $object);
echo compiletpl("scripts/show/data", array(), $object);
echo compiletpl("scripts/show/children", array("children_show_page"=>$args['children_show_page']), $object);
if ($object->getMeta("show_comments", 0) == 1)
	echo compiletpl("scripts/show/comments", array(), $object);
?>