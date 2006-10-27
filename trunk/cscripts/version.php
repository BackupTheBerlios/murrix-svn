<?

class csVersion extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
	{
		global $version;
		$stdout = $version;
		return true;
	}
}

?>