<div class="buttons">
<?
	$object = new mObject(getNode("/root/public/menu"));
	$invert = $object->getMeta("sort_direction", "") == "asc" ? "!" : "";
	$children = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' AND !property:class_name='comment' AND !property:class_name='poll_answer' AND !property:class_name='image_region' NODESORTBY property:version SORTBY $invert".$object->getMeta("sort_by", "property:name"));

	$children = getReadable($children);
	foreach ($children as $child)
	{
		echo cmd($child->getName(), "exec=show&node_id=".$child->getNodeId(), array("class"=>button));
	}
	?>
	<div class="clear"></div>
</div>