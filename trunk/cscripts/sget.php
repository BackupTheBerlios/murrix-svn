<?

class csSget extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
	{
		if (!empty($args))
		{
			$stdout = getSetting($args);
		}
		else
		{
			$stdout = "Usage: sget [settingname]\n";
			$stdout .= "Example: sget TITLE";
		}
			
		return true;
	}
}

?>