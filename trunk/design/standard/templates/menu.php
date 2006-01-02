<?
if ($menu_id > 0)
{
	$children = fetch("FETCH node WHERE link:node_top='$menu_id' AND link:type='sub' AND !property:class_name='comment' NODESORTBY property:version SORTBY property:name");

	if (count($children) > 0)
	{
		$menuroot = new mObject($menu_id);
		?>
		<div id="menu">
			<div class="header">
				<?=cmd($menuroot->getName(), "Exec('show', 'zone_main', Hash('path', '".$menuroot->getPath()."'))")?>
			</div>
			
			<div class="menu_items">
			<?
				foreach ($children as $child)
					include(gettpl("small_line", $child));
			?>
			</div>
		</div>
		<?
	}
}
?>