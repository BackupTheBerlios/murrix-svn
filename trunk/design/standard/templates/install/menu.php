<div style="padding-left: 10px; text-align: left; float: left; border-right: 1px solid #5B5B7A; width: 100px; height: 250px; font-size: 14px; font-weight: bold;">
<?
	if ($args['stage'] == 1)
		echo "<span style=\"color: red;\">Welcome</span>";
	else
		echo cmd("Welcome", "Exec('install', 'zone_main', Hash('stage', '1'))");
	echo "<br/><br/>";

	if ($args['stage'] == 2)
		echo "<span style=\"color: red;\">License</span>";
	else
		echo cmd("License", "Exec('install', 'zone_main', Hash('stage', '2'))");
	echo "<br/><br/>";

	if ($args['stage'] == 3)
		echo "<span style=\"color: red;\">Adminaccount</span>";
	else
		echo cmd("Adminaccount", "Exec('install', 'zone_main', Hash('stage', '3'))");
	echo "<br/><br/>";

	if ($args['stage'] == 4)
		echo "<span style=\"color: red;\">Database</span>";
	else
		echo cmd("Database", "Exec('install', 'zone_main', Hash('stage', '4'))");
	echo "<br/><br/>";

	if ($args['stage'] == 5)
		echo "<span style=\"color: red;\">Site</span>";
	else
		echo cmd("Site", "Exec('install', 'zone_main', Hash('stage', '5'))");
	echo "<br/><br/>";

	if ($args['stage'] == 6)
		echo "<span style=\"color: red;\">Finish</span>";
	else
		echo cmd("Finish", "Exec('install', 'zone_main', Hash('stage', '6'))");
?>
</div>
