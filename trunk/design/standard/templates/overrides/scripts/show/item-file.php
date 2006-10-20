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
		$data = $thumbnail->Show(true);
}

if (!empty($data))
	$img = $data;
else
	$img = img(geticon($type, 128));

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