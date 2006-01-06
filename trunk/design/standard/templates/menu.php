<?
$menu_id = resolvePath("/Root/Public/Menu");
$custom_menu_id = resolvePath("/Root/Home/".$_SESSION['murrix']['user']->getName()."/Menu");

if ($menu_id > 0 || $custom_menu_id > 0)
{

	if ($menu_id > 0 && $custom_menu_id > 0)
		$link_top = "(link:node_top='$menu_id' OR link:node_top='$custom_menu_id')";
	else if ($menu_id > 0)
		$link_top = "link:node_top='$menu_id'";
	else if ($custom_menu_id > 0)
		$link_top = "link:node_top='$custom_menu_id'";

	$children = fetch("FETCH node WHERE $link_top AND link:type='sub' AND !property:class_name='comment' AND !property:class_name='folder' NODESORTBY property:version SORTBY !property:name");
	
	$subfolders = fetch("FETCH node WHERE $link_top AND link:type='sub' AND !property:class_name='comment' AND property:class_name='folder' NODESORTBY property:version SORTBY !property:name");

	if (count($children) > 0 || count($subfolders))
	{
	?>
		<div id="menu">
			<div class="header">
				<?=ucf(i18n("menu"))?>
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
							<?=cmd($subfolder->getName(), "Exec('show', 'zone_main', Hash('path', '".$subfolder->getPathInTree()."'))")?>
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