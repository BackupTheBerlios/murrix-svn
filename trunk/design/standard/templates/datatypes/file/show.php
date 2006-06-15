<?
$extension = pathinfo($args['fvalue'], PATHINFO_EXTENSION);
$type = getfiletype($extension);

$_SESSION['murrix']['rightcache']['file'][] = $args['value_id'];

?><a href="?file=<?=$args['value_id']?>"><?

if ($type == "image")
{
	$maxsize = getSetting("THUMBSIZE", 150);
	$thumbnail = getThumbnail($args['value_id'], $maxsize, $maxsize);
	
	if ($thumbnail !== false)
		echo $thumbnail->Show(true)."<br/>";
	else
		echo img(geticon(getfiletype($extension), 16))." ";
}
else
	echo img(geticon(getfiletype($extension), 16))." ";
	
echo $args['value'];
?>
</a>