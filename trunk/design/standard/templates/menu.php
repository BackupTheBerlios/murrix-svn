<?
$class = "sidebar";
?>
<div class="title">
	<a class="right" id="menu_right" href="javascript:void(null)" onclick="toggleSidebarContainer('menu')">&uarr;&uarr;</a>
	<a class="left" id="menu_left" href="javascript:void(null)" onclick="toggleSidebarContainer('menu')">&uarr;&uarr;</a>
	<?=ucf(i18n("menu"))?>
</div>
<div id="menu_container" class="container">
<?
	echo cmd(img(getIcon("console"))." Console", "exec=console", $class);
	echo cmd(img(getIcon("date"))." Calendar", "exec=calendar", $class);
	echo cmd(img(getIcon("search"))." Search", "exec=search", $class);
	if (!empty($_SESSION['murrix']['user']->password))
		echo cmd(img(geticon("password"))." ".ucf(i18n("change password")), "exec=console&initcmd=upass", $class);
?>
</div>
<?
$home_id = $_SESSION['murrix']['user']->home_id;
	
if ($home_id > 0)
{
	$home = new mObject($home_id);
	?>
	<div class="title">
		<a class="right" id="<?=$home_id?>_right" href="javascript:void(null)" onclick="toggleSidebarContainer('<?=$home_id?>')">&uarr;&uarr;</a>
		<a class="left" id="<?=$home_id?>_left" href="javascript:void(null)" onclick="toggleSidebarContainer('<?=$home_id?>')">&uarr;&uarr;</a>
		<?=cmd(ucf($home->getName()), "exec=show&node_id=$home_id", $class)?>
	</div>
	<div id="<?=$home_id?>_container" class="container">
	<?
	$children = fetch("FETCH node WHERE link:node_top='$home_id' AND link:type='sub' AND !property:class_name='comment' NODESORTBY property:version SORTBY property:name");
	
	foreach ($children as $child)
		include(gettpl("small_line", $child));
		
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
		?>
		<div class="title">
			<a class="right" id="<?=$home_id?>_right" href="javascript:void(null)" onclick="toggleSidebarContainer('<?=$home_id?>')">&uarr;&uarr;</a>
			<a class="left" id="<?=$home_id?>_left" href="javascript:void(null)" onclick="toggleSidebarContainer('<?=$home_id?>')">&uarr;&uarr;</a>
			<?=cmd(ucf($home->getName()), "exec=show&node_id=$home_id", $class)?>
		</div>
		<div id="<?=$home_id?>_container" class="container">
		<?
		$children = fetch("FETCH node WHERE link:node_top='$home_id' AND link:type='sub' AND !property:class_name='comment' NODESORTBY property:version SORTBY property:name");
		
		foreach ($children as $child)
			include(gettpl("small_line", $child));
			
		?></div><?
	}
}
?>
