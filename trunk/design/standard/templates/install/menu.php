<div style="text-align: left; font-size: 14px; font-weight: bold;">
<?
	if ($args['stage'] == 1)
		echo "<span style=\"color: red;\">Welcome</span>";
	else
		echo "Welcome";
// 		echo cmd("Welcome", "Exec('install', 'zone_main', Hash('stage', '1'))");
	echo "<br/><br/>";

	if ($args['stage'] == 2)
		echo "<span style=\"color: red;\">License</span>";
	else
		echo "License";
// 		echo cmd("License", "Exec('install', 'zone_main', Hash('stage', '2'))");
	echo "<br/><br/>";

	if ($args['stage'] == 3)
		echo "<span style=\"color: red;\">Adminaccount</span>";
	else
		echo "Adminaccount";
// 		echo cmd("Adminaccount", "Exec('install', 'zone_main', Hash('stage', '3'))");
	echo "<br/><br/>";

	if ($args['stage'] == 4)
		echo "<span style=\"color: red;\">Database</span>";
	else
		echo "Database";
// 		echo cmd("Database", "Exec('install', 'zone_main', Hash('stage', '4'))");
	echo "<br/><br/>";

	if ($args['stage'] == 5)
		echo "<span style=\"color: red;\">Database Tests</span>";
	else
		echo "Database Tests";
// 		echo cmd("Database", "Exec('install', 'zone_main', Hash('stage', '4'))");
	echo "<br/><br/>";

	if ($args['stage'] == 6)
		echo "<span style=\"color: red;\">Theme</span>";
	else
		echo "Theme";
// 		echo cmd("Theme", "Exec('install', 'zone_main', Hash('stage', '5'))");
	echo "<br/><br/>";

	if ($args['stage'] == 7)
		echo "<span style=\"color: red;\">Finish</span>";
	else
		echo "Finish";
// 		echo cmd("Finish", "Exec('install', 'zone_main', Hash('stage', '6'))");
?>
</div>