<div class="menu">
<?
	$list = array();

	$list['preinstall'] = "Pre-installation checks";
	$list['license'] = "License";
	$list['database'] = "Database";
	$list['databasecheck'] = "Database checks";
	$list['config'] = "Configuration";
	$list['finish'] = "Finish";
	
	$before = true;
	foreach ($list as $key => $value)
	{
		if ($key == $args['action'])
		{
			echo "<div class=\"item_selected\">$value</div>";
			$before = false;
		}
		else
		{
			if ($before)
				echo "<div class=\"item_before\">$value</div>";
			else
				echo "<div class=\"item_after\">$value</div>";
		}
	}
?>
</div>