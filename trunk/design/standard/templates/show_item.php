<?
$img = cmd(img(geticon($child->getIcon(), 128)), "Exec('show','zone_main', Hash('path', '".$child->getPathInTree()."'))");
?>
<div class="show_item">
	<?=$img?>
	<br/>
	<?=cmd($child->getName(), "Exec('show','zone_main', Hash('path', '".$child->getPathInTree()."'))")?>
</div>