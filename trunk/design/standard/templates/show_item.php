<div class="show_item">
	<?=cmd(img(geticon($child->getIcon(), 128)), "exec=show&node_id=".$child->getNodeId())?>
	<br/>
	<?=cmd($child->getName(), "exec=show&node_id=".$child->getNodeId())?>
</div>