<?
$divider = empty($args['divider']) ? "/" : $args['divider'];

$parts = explode("/", $args['path']);
array_shift($parts);

$path2 = "";
$count = 0;
foreach ($parts as $part)
{
	$path2 .= "$divider$part";
	if ($count > 0)
		echo "&nbsp;$divider&nbsp;";

	$object = new mObject(getNode($path2));
	if ($object->hasRight("read"))
		echo cmd($part, "exec=show&node_id=".$object->getNodeId());
	else
		echo $part;
		
	$count++;
}
?>