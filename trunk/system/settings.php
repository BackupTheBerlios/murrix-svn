<?

function getSetting($name, $default = "")
{
	$table = new mTable("settings");
	
	$settings = $table->get("`name`='$name' AND (`theme`='any' OR `theme`='".$_SESSION['murrix']['theme']."')");
	
	if (count($settings) == 0)
		return $default;
		
	return $settings[0]['value'];
}

function setSetting($name, $value, $theme = "")
{
	$table = new mTable("settings");
	
	if (empty($theme))
		$theme = $_SESSION['murrix']['theme'];
			
	$settings = $table->get("`name`='$name' AND (`theme`='any' OR `theme`='$theme')");
	
	if (count($settings) > 0)
	{
		$settings[0]['value'] = $value;
		
		if (empty($value))
			return $table->remove($settings[0]['id']);
		else
			return $table->update($settings[0]['id'], $settings);
	}
	else if (!empty($value))
	{
		$setting = array("name" => $name, "value" => $value, "theme" => $theme);
		
		return $table->insert($setting);
	}
}

?>