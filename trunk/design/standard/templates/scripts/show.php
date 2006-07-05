<?
echo compiletpl("title_show", array(), $object);
echo compiletpl("data_show", array(), $object);
echo compiletpl("children_show", array(), $object);
if ($object->getMeta("show_comments", 0) == 1)
	echo compiletpl("comments_show", array(), $object);
?>