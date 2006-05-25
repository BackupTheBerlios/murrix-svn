<?
$filename = $child->getVarValue("file");
$value_id = $child->resolveVarName("file");
$pathinfo = pathinfo($filename);
$type = getfiletype($pathinfo['extension']);

$data = "";

if ($type == "image")
{
	$maxsize = 150;
	$angle = $child->getMeta("angle");
	
	if (empty($angle))
		$angle = GetFileAngle($filename);
		
	$thumbnail = getThumbnail($value_id, $maxsize, $maxsize, $angle);
	
	$_SESSION['murrix']['rightcache']['thumbnail'][] = $thumbnail->id;
	
	if ($thumbnail !== false)
		$data = $thumbnail->Show(true);
}

if (!empty($data))
	$img = cmd($data, "Exec('show','zone_main',Hash('node_id','".$child->getNodeId()."'))");
else
	$img = cmd(img(geticon(getfiletype($pathinfo['extension']), 128)), "Exec('show','zone_main',Hash('node_id','".$child->getNodeId()."'))");

?>
<div class="show_item">
	<?=$img?>
	<br/>
	<?=cmd($child->getName(), "Exec('show','zone_main',Hash('node_id','".$child->getNodeId()."'))")?>
</div>