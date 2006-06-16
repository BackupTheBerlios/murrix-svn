<?

class csSlist extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$settings = getSettings();
		
		if ($args == "-l")
		{
			$stdout .= "total ".count($settings)."\n";
			if (count($settings) > 0)
			{
				$stdout .= "<table cellspacing=\"0\">";
				$stdout .= "<tr class=\"table_title\">";
				$stdout .= "<td>Name</td>";
				$stdout .= "<td>Value</td>";
				$stdout .= "<td>Theme</td>";
				$stdout .= "</tr>";
				foreach ($settings as $setting)
				{
					$stdout .= "<tr>";
					$stdout .= "<td>".$setting['name']."</td>";
					$stdout .= "<td>".$setting['value']."</td>";
					$stdout .= "<td>".$setting['theme']."</td>";
					$stdout .= "</tr>";
				}
				$stdout .= "</table>";
			}
		}
		else
		{
			foreach ($settings as $setting)
				$stdout .= $setting['name']." ";
		}
		
		return true;
	}
}

?>