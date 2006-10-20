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

	$img = $thumbnail->Show(true);
}
else
	$img = img(geticon($object->getIcon(), 128));

$name = $object->getName();

if ($args['disabled'] != true)
{
	$name = cmd($name, "exec=show&node_id=".$object->getNodeId());
	$img = cmd($img, "exec=show&node_id=".$object->getNodeId());
}

?>
<div class="show_item">
	<table cellspacing="0" style="width: 100%; height: 100%;">
		<tr>
			<td>
			</td>
		</tr>
		<tr>
			<td>
				<?=$img?>
			</td>
		</tr>
		<tr>
			<td valign="bottom">
				<div class="name_label">
					<?=$name?>
				</div>
			</td>
		</tr>
		
	</table>
</div>