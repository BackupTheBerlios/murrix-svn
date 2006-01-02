<?

if (count($children) > 0)
{
	include(gettpl("pager_start", $object));

	switch ($view_slected)
	{
		case "thumbnailes":
		?>
			<div class="show_item_wrapper">
			<?
				for ($i = $start; $i < $end; $i++)
				{
					$child = $children[$i];
					include(gettpl("show_item", $child));
				}
				?>
				<div class="clear"></div>
			</div>
			<?
			break;
	
		case "list":
		default:
			for ($i = $start; $i < $end; $i++)
			{
				$child = $children[$i];
				include(gettpl("show_line", $child));
			}
			break;
	}

	include(gettpl("pager_end", $object));
}
?>
