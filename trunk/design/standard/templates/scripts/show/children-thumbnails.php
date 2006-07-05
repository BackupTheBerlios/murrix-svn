<div class="show_item_wrapper">
<?
	for ($i = $args['start']; $i < $args['end']; $i++)
	{
		$child = $args['objects'][$i];
		include(gettpl("show_item", $child));
	}
	?>
	<div class="clear"></div>
</div>
