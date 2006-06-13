<?

class csGetsetting extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		if (!empty($args))
		{
			$stdout = getSetting($args);
		}
			
		return true;
	}
}

?>