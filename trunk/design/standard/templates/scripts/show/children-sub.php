<?
for ($i = $args['start']; $i < $args['end']; $i++)
{
	$object = $args['objects'][$i];
	?>
	<div class="sub_title">
		<a class="right" href="javascript:void(null)" onclick="toggleSidebarContainer('<?=$object->getNodeId()?>')"><?=img(imgpath("1downarrow.png"), "", "", $object->getNodeId()."_arrow")?></a>
		<?=cmd(img(getIcon($object->getIcon()))."&nbsp;".$object->getName(), "exec=show&node_id=".$object->getNodeId())?>
		<div class="clear"></div>
	</div>
	<div id="<?=$object->getNodeId()?>_container">
		<?=compiletpl("scripts/show/data", array(), $object)?>
		<?=compiletpl("scripts/show/children", array(), $object)?>
	</div>
<?
}
?>