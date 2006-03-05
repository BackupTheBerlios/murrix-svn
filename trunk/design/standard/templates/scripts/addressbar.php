<?
$parts = explode("/", $path);
array_shift($parts);

$path2 = "";

foreach ($parts as $part)
{
	$path2 .= "/$part";
	echo "&nbsp;/&nbsp;";

	$object = new mObject(resolvePath($path2));
	if ($object->hasRight("read"))
		echo cmd($part, "Exec('show','zone_main',Hash('node_id','".$object->getNodeId()."'))");
	else
		echo $part;
}
?>