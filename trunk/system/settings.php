<?

function getSettings()
{
	$table = new mTable("settings");
	return $table->get();
}

function clearSettingsCache()
{
	unset($GLOBALS['cache']['settings']);
}

function fillSettingsCache()
{
	if (!isset($GLOBALS['cache']['settings']))
	{
		$table = new mTable("settings");
		$settings = $table->get();
		
		if (count($settings) == 0)
			$GLOBALS['cache']['settings'] = array();
		else
		{
			foreach ($settings as $setting)
				$GLOBALS['cache']['settings'][$setting['theme']][$setting['name']] = $setting['value'];
		}
	}
}

function getSetting($name, $default = "")
{
	fillSettingsCache();
	
	$theme = $_SESSION['murrix']['theme'];

	if (isset($GLOBALS['cache']['settings'][$theme][$name]))
		return $GLOBALS['cache']['settings'][$theme][$name];
		
	if (isset($GLOBALS['cache']['settings']["any"][$name]))
		return $GLOBALS['cache']['settings']["any"][$name];
		
	return $default;
}

function setSetting($name, $value, $theme = "")
{
	clearSettingsCache();
	
	$table = new mTable("settings");
	
	if (empty($theme))
		$theme = $_SESSION['murrix']['theme'];
			
	$settings = $table->get("`name`='$name' AND (`theme`='any' OR `theme`='$theme')");
	
	if (count($settings) > 0)
	{
		$settings[0]['value'] = $value;
		
		if (empty($value))
		{
			if (!$table->remove($settings[0]['id']))
				return $table->error;
			else
				return true;
		}
		else
		{
			if (!$table->update($settings[0]['id'], $settings[0]))
				return $table->error;
			else
				return true;
		}
	}
	else if (!empty($value))
	{
		$setting = array("name" => $name, "value" => $value, "theme" => $theme);
		
		if (!$table->insert($setting))
			return $table->error;
		else
			return true;
	}
	
	return ucf(i18n("no such setting"));
}

?>