<?
$thumb_id = $child->getVarValue("thumbnail_id");
if (!empty($thumb_id))
{
	$thumbnail = new mThumbnail($thumb_id);
	$img = cmd($thumbnail->Show(true), "Exec('show','zone_main', Hash('path', '".$child->getPath()."'))");
}
else
{
	$pathinfo = pathinfo($child->getVarValue("file"));
	$img = cmd(img(geticon(getfiletype($pathinfo['extension']), 128)), "Exec('show','zone_main', Hash('path', '".$child->getPath()."'))");
}

?>
<div class="show_item">
	<?=$img?>
	<br/>
	<?=cmd($child->getName(), "Exec('show','zone_main', Hash('path', '".$child->getPath()."'))")?>
</div>