<?

class csWhoami extends CScript
{
	function exec($stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$stdout = $_SESSION['murrix']['user']->getName();
		return true;
	}
}

?>