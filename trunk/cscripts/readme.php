<?

class csReadme extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$stdout = "<a target=\"top\" href=\"./docs/README.txt\">Readme</a>";
		return true;
	}
}

?>