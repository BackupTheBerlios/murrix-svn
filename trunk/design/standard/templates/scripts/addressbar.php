<?
$parts = explode("/", $path);
array_shift($parts);

$path2 = "";

foreach ($parts as $part)
{
	$path2 .= "/$part";
	echo "&nbsp;/&nbsp;";
	echo cmd($part, "Exec('show', 'zone_main', Hash('path', '$path2'))");
}
?>