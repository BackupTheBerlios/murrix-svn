<div id="menu">
	<div class="header">
		<?=ucf(i18n("menu"))?>
	</div>
	<div class="menu_items">
		<?=cmd(img(getIcon("console"))." Console", "Exec('console','zone_main','')")?>
		<?=cmd(img(getIcon("date"))." Calendar", "Exec('calendar','zone_main','')")?>
	</div>
	<?
	$menu_id = getNode("/root/public/menu");
	$custom_menu_id = getNode("/root/home/users/".$_SESSION['murrix']['user']->username."/menu");

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
							<?=cmd(img(geticon($subfolder->getIcon()))." ".$subfolder->getName(), "Exec('show','zone_main',Hash('node_id','".$subfolder->getNodeId()."'))")?>
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
		}
	}

	$homes = fetch("FETCH node WHERE (link:node_top='".getNode("/root/home/users")."' OR link:node_top='".getNode("/root/home/groups")."') AND link:type='sub' AND !property:class_name='comment' NODESORTBY property:version SORTBY property:name");
	$homes = getReadable($homes);

	if (count($homes) > 0)
	{
	?>
		<div class="subheader">
			<?=img(geticon("home"))." ".ucf(i18n("home folders"))?>
		</div>
	
		<div class="menu_items">
		<?
			foreach ($homes as $child)
				include(gettpl("small_line", $child));
		?>
		</div>
	<?
	}
?>
</div>