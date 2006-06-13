<?

class csPwd extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$stdout = $_SESSION['murrix']['path'];
		return true;
	}
}

?>