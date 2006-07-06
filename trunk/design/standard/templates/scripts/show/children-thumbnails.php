<div class="show_item_wrapper">
<?
	for ($i = $args['start']; $i < $args['end']; $i++)
		echo compiletpl("scripts/show/item", array(), $args['objects'][$i]);
	?>
	<div class="clear"></div>
</div>
