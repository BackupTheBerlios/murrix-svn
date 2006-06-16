<?

class csSset extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		if (!empty($args))
		{
			$args_split = splitArgs($args);
			
			if (count($args_split) >= 2)
			{
				$name = $args_split[0];
				$value = $args_split[1];
				
				if (isset($args_split[2]))
					$theme = $args_split[2];
			
				if (!isAdmin())
				{
					$stderr = ucf(i18n("not enough rights"));
					return true;
				}
				
				$result = setSetting($name, $value, $theme);
				
				if ($result === true)
					$stdout = ucf(i18n("setting set successfully"));
				else
					$stdout = $result;
			}
		}
		else
		{
			$stdout = "Usage: sset [settingname] [value]\n";
			$stdout .= "Example: sset TITLE \"Murrix title\"";
		}
		
		return true;
	}
}

?>