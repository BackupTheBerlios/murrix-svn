<nobr>
	<?=cmd(img(geticon($child->getIcon()))."&nbsp;".$child->getName(), "Exec('show','zone_main', Hash('path', '".$child->getPathInTree()."'));")?>
</nobr>