<?
if ($menu_id > 0)
{
	$children = fetch("FETCH node WHERE link:node_top='$menu_id' AND link:type='sub' AND !property:class_name='comment' AND !property:class_name='folder' NODESORTBY property:version SORTBY !property:name");
	
	$subfolders = fetch("FETCH node WHERE link:node_top='$menu_id' AND link:type='sub' AND !property:class_name='comment' AND property:class_name='folder' NODESORTBY property:version SORTBY !property:name");

	$menuroot = new mObject($menu_id);

	if (count($children) > 0 || count($subfolders))
	{
	?>
		<div id="menu">
			<div class="header">
				<?=cmd($menuroot->getName(), "Exec('show', 'zone_main', Hash('path', '".$menuroot->getPath()."'))")?>
			</div>
			<?
			if (count($children) > 0)
			{
				?>
				<div class="menu_items">
				<?
					foreach ($children as $child)
						include(gettpl("small_line", $child));
				?>
				</div>
				<?
			}
		
			if (count($subfolders) > 0)
			{
				foreach ($subfolders as $subfolder)
				{
					$children = fetch("FETCH node WHERE link:node_top='".$subfolder->getNodeId()."' AND link:type='sub' AND !property:class_name='comment' NODESORTBY property:version SORTBY !property:name");
		
					if (count($children) > 0)
					{
						?>
						<div class="subheader">
							<?=cmd($subfolder->getName(), "Exec('show', 'zone_main', Hash('path', '".$subfolder->getPath()."'))")?>
						</div>
		
						<div class="menu_items">
						<?
							foreach ($children as $child)
								include(gettpl("small_line", $child));
						?>
						</div>
						<?
					}
				}
			}
		?>
		</div>
	<?
	}
}
?>