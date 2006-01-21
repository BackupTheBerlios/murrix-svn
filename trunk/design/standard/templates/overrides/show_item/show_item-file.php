<?
$thumb_id = $child->getVarValue("thumbnail_id");
$filename = $child->getVarValue("file");
$pathinfo = pathinfo($filename);

$showtumb = false;

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
				echo "Failed to create thumbnail<br/>";
			else
				$showtumb = true;
		}
	}
	else
		$showtumb = true;
}

if ($showtumb)
	$img = cmd($thumbnail->Show(true), "Exec('show','zone_main', Hash('path', '".$child->getPathInTree()."'))");
else
	$img = cmd(img(geticon(getfiletype($pathinfo['extension']), 128)), "Exec('show','zone_main', Hash('path', '".$child->getPathInTree()."'))");

?>
<div class="show_item">
	<?=$img?>
	<br/>
	<?=cmd($child->getName(), "Exec('show','zone_main', Hash('path', '".$child->getPathInTree()."'))")?>
</div>