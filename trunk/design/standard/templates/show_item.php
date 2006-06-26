<div class="show_item">
<?
	$img = "<div style=\"height: 20px;\"></div>".img(geticon($child->getIcon(), 128));
	if ($disabled === true)
		echo "$img<br/>".$child->getName();
	else
		echo cmd("$img<br/>".$child->getName(), "exec=show&node_id=".$child->getNodeId());
?>
</div>