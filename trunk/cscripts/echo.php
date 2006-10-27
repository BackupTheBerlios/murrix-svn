<?

class csEcho extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
	{
		$stdout = $args;
		return true;
	}
}

?>