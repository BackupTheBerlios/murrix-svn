<?
$thumb_id = $child->getVarValue("thumbnail");

if (!empty($thumb_id))
{
	$maxsize = 128;
	
	$thumbnail = new mThumbnail($thumb_id);
	
	if ($thumbnail->height > $thumbnail->width && $maxsize > 0)// höjden = maxsize;
	{
		$h = $maxsize;
		$w = $thumbnail->width * ($maxsize / $thumbnail->height);
	}
	else//bredden = maxsize
	{
		$h = $thumbnail->height * ($maxsize / $thumbnail->width);
		$w = $maxsize;
	}
	
	$thumbnail->height = $h;
	$thumbnail->width = $w;
	
	$_SESSION['murrix']['rightcache']['thumbnail'][] = $thumbnail->id;

	$img = cmd($thumbnail->Show(true), "exec=show&node_id=".$child->getNodeId());
}
else
	$img = cmd(img(geticon($child->getIcon(), 128)), "exec=show&node_id=".$child->getNodeId());

?>
<div class="show_item">
	<?=$img?>
	<br/>
	<?=cmd($child->getName(), "exec=show&node_id=".$child->getNodeId())?>
</div>