<?

class csEcho extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$stdout = $args;
		return true;
	}
}

?>