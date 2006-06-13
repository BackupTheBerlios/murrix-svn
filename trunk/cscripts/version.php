<?

class csVersion extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		global $version;
		$stdout = $version;
		return true;
	}
}

?>