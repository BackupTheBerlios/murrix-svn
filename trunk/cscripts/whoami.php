<?

class csWhoami extends CScript
{
	function exec($stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$stdout = $_SESSION['murrix']['user']->name." (".$_SESSION['murrix']['user']->username.")";
		return true;
	}
}

?>