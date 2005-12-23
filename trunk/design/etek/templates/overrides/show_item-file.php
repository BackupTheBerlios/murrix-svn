<?
$thumb_id = $child->getVarValue("thumbnail_id");
$size = "width: 160px; height: 160px;";
if (!empty($thumb_id))
{
	$thumbnail = new mThumbnail($thumb_id);
	$img = cmd($thumbnail->Show(true), "Exec('show','zone_main', Hash('path', '".$child->getPath()."'))", "titel");
	/*$size = $thumbnail->height > $thumbnail->width ? $thumbnail->height : $thumbnail->width;
	$size += 10;
	$size = "width: {$size}px; height: {$size}px;";*/
}
else
{
	$pathinfo = pathinfo($child->getVarValue("file"));
	$img = cmd(img(geticon(getfiletype($pathinfo['extension']), 128)), "Exec('show','zone_main', Hash('path', '".$child->getPath()."'))", "titel");
}
?>
<div style="text-align: center; float: left; padding: 5px; <?=$size?>">
	<table class="invisible" cellspacing="0" width="100%" height="100%">
		<tr>
			<td valign="center">
				<?=$img?>
				<br/>
				<?=cmd($child->getName(), "Exec('show','zone_main', Hash('path', '".$child->getPath()."'))", "titel")?>
			</td>
		</tr>
	</table>
</div>