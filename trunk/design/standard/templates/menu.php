<?
$class = "sidebar";
?>
<div class="title">
	<a class="right" href="javascript:void(null)" onclick="toggleSidebarContainer('menu')"><?=img(imgpath("1downarrow.png"), "", "", "menu_arrow")?></a>
	<?=ucf(i18n("menu"))?>
</div>
<div id="menu_container" class="container">
<?
	echo cmd(img(getIcon("console"))." ".ucf(i18n("console")), "exec=console", $class);
	echo cmd(img(getIcon("date"))." ".ucf(i18n("calendar")), "exec=calendar", $class);
	echo cmd(img(getIcon("search"))." ".ucf(i18n("search")), "exec=search", $class);
	if (!empty($_SESSION['murrix']['user']->password))
		echo cmd(img(geticon("password"))." ".ucf(i18n("change password")), "exec=console&initcmd=upass", $class);
?>
</div>
<?
$home_id = $_SESSION['murrix']['user']->home_id;
	
if ($home_id > 0)
{
	$home = new mObject($home_id);
	
	$children = fetch("FETCH node WHERE link:node_top='$home_id' AND link:type='sub' AND !property:class_name='comment' NODESORTBY property:version SORTBY property:name");
	
	?>
	<div class="title">
	<?
		if (count($children) > 0)
		{
		?>
			<a class="right" href="javascript:void(null)" onclick="toggleSidebarContainer('<?=$home_id?>')"><?=img(imgpath("1downarrow.png"), "", "", $home_id."_arrow")?></a>
		<?
		}
		echo cmd(ucf($home->getName()), "exec=show&node_id=$home_id", $class);
	?>
	</div>
	<div id="<?=$home_id?>_container" class="container">
	<?
	$children = fetch("FETCH node WHERE link:node_top='$home_id' AND link:type='sub' AND !property:class_name='comment' NODESORTBY property:version SORTBY property:name");
	
	foreach ($children as $child)
		echo compiletpl("scripts/show/small_line", array("class"=>$class), $child);
		
	?></div><?
}

$groups = $_SESSION['murrix']['user']->getGroups();

foreach ($groups as $group_name)
{
	$group = new mGroup();
	$group->setByName($group_name);
	$home_id = $group->home_id;
	
	if ($home_id > 0)
	{
		$home = new mObject($home_id);
		
		$children = fetch("FETCH node WHERE link:node_top='$home_id' AND link:type='sub' AND !property:class_name='comment' NODESORTBY property:version SORTBY property:name");
		?>
		<div class="title">
		<?
			if (count($children) > 0)
			{
			?>
				<a class="right" href="javascript:void(null)" onclick="toggleSidebarContainer('<?=$home_id?>')"><?=img(imgpath("1downarrow.png"), "", "", $home_id."_arrow")?></a>
			<?
			}
			echo cmd(ucf($home->getName()), "exec=show&node_id=$home_id", $class);
		?>
		</div>
		<div id="<?=$home_id?>_container" class="container">
		<?
			foreach ($children as $child)
				echo compiletpl("scripts/show/small_line", array("class"=>$class), $child);
		?>
		</div>
	<?
	}
}
?>