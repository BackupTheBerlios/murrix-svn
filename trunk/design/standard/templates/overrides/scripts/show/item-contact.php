<?
$thumb_id = $object->getVarValue("thumbnail");

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

	$img = "<div style=\"height: ".ceil((168-$h)/2)."px;\"></div>".$thumbnail->Show(true);
}
else
	$img = "<div style=\"height: 20px;\"></div>".img(geticon($object->getIcon(), 128));

?>
<div class="show_item">
<?
	if ($args['disabled'] === true)
		echo "$img<br/>".$object->getName();
	else
		echo cmd("$img<br/>".$object->getName(), "exec=show&node_id=".$object->getNodeId());
?>
</div>