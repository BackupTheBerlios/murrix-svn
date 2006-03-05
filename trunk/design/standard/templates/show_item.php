<div class="show_item">
	<?=cmd(img(geticon($child->getIcon(), 128)), "Exec('show','zone_main',Hash('node_id','".$child->getNodeId()."'))")?>
	<br/>
	<?=cmd($child->getName(), "Exec('show','zone_main',Hash('node_id','".$child->getNodeId()."'))")?>
</div>