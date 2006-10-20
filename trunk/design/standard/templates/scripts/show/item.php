<?
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