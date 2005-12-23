<?

if (count($children) > 0)
{
	include(gettpl("pager_start", $object));

	switch ($view_slected)
	{
		case "thumbnailes":
		?>
			<div class="main_bg" style="margin-top: 5px; padding-bottom: 15px;">
			<?
				for ($i = $start; $i < $end; $i++)
				{
					$child = $children[$i];
					include(gettpl("show_item", $child));
				}
				?>
				<div style="clear: both"></div>
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
