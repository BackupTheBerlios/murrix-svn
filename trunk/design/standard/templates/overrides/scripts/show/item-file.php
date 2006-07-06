<?
$filename = $object->getVarValue("file");
$value_id = $object->resolveVarName("file");

$type = getfiletype(pathinfo($filename, PATHINFO_EXTENSION));

$data = "";

if ($type == "image")
{
	$maxsize = getSetting("THUMBSIZE", 150);
	$angle = $object->getMeta("angle", "");
	
	$thumbnail = getThumbnail($value_id, $maxsize, $maxsize, $angle);
	
	$_SESSION['murrix']['rightcache']['thumbnail'][] = $thumbnail->id;
	
	if ($thumbnail !== false)
		$data = "<div style=\"height: ".ceil((168-$thumbnail->height)/2)."px;\"></div>".$thumbnail->Show(true);
}

if (!empty($data))
	$img = $data;
else
	$img = "<div style=\"height: 20px;\"></div>".img(geticon($type, 128));

?>
<div class="show_item">
<?
	if ($args['disabled'] === true)
		echo "$img<br/>".$object->getName();
	else
		echo cmd("$img<br/>".$object->getName(), "exec=show&node_id=".$object->getNodeId());
?>
</div>