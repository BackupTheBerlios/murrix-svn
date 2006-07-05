<?
for ($i = $args['start']; $i < $args['end']; $i++)
{
	$child = $args['objects'][$i];
	include(gettpl("show_line", $child));
}
?>