<?

class csEcho extends CScript
{
	function exec($stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$stdout = $stdin;
		return true;
	}
}

?>