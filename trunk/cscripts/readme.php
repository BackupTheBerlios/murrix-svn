<?

class csReadme extends CScript
{
	function exec($stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$stdout = "<a target=\"top\" href=\"./docs/README.txt\">Readme</a>";
		return true;
	}
}

?>