<?

class csVersion extends CScript
{
	function exec($stdin, &$stdout, &$stderr, &$response, &$system)
	{
		global $version;
		$stdout = $version;
		return true;
	}
}

?>