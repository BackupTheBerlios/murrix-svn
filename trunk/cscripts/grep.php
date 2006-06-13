<?

class csGrep extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$lines = explode("\n", $stdin);
		
		foreach ($lines as $line)
		{
			if (!(strstr($line, $args) === false))
				$stdout .= "$line\n";
		}
	
		return true;
	}
}

?>