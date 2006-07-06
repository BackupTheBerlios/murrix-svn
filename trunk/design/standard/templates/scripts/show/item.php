<div class="show_item">
<?
	$img = "<div style=\"height: 20px;\"></div>".img(geticon($object->getIcon(), 128));
	if ($args['disabled'] === true)
		echo "$img<br/>".$object->getName();
	else
		echo cmd("$img<br/>".$object->getName(), "exec=show&node_id=".$object->getNodeId());
?>
</div>