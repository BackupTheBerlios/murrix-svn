<?
$parts = explode("/", $args['path']);
array_shift($parts);

$path2 = "";

foreach ($parts as $part)
{
	$path2 .= "/$part";
	echo "&nbsp;/&nbsp;";

	$object = new mObject(getNode($path2));
	if ($object->hasRight("read"))
		echo cmd($part, "exec=show&node_id=".$object->getNodeId());
	else
		echo $part;
}
?>