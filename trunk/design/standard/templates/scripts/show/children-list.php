<?
for ($i = $args['start']; $i < $args['end']; $i++)
	echo compiletpl("scripts/show/line", array(), $args['objects'][$i]);
?>