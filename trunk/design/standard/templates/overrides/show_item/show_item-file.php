<?
$thumb_id = $child->getVarValue("thumbnail_id");
$filename = $child->getVarValue("file");
$pathinfo = pathinfo($filename);

if (!empty($thumb_id))
{
	$thumbnail = new mThumbnail($thumb_id);

	if ($thumbnail->getRebuild())
	{
		$angle = $child->getMeta("angle");
		
	
		if (empty($angle))
			$angle = GetFileAngle($filename);
	
		if ($angle < 0) $angle = 360+$angle;
		else if ($angle > 360) $angle = 360-$angle;
	
		$maxsize = 150;
		if ($thumbnail->CreateFromFile($filename, $pathinfo['extension'], $maxsize, $maxsize, $angle))
		{
			if (!$thumbnail->Save())
				echo "Failed to create thumbnail<br>";
		}
	}
	
	$img = cmd($thumbnail->Show(true), "Exec('show','zone_main', Hash('path', '".$child->getPath()."'))");
}
else
{
	$img = cmd(img(geticon(getfiletype($pathinfo['extension']), 128)), "Exec('show','zone_main', Hash('path', '".$child->getPath()."'))");
}

?>
<div class="show_item">
	<?=$img?>
	<br/>
	<?=cmd($child->getName(), "Exec('show','zone_main', Hash('path', '".$child->getPath()."'))")?>
</div>