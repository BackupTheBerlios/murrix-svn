<?

class csPwd extends CScript
{
	function exec($stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$stdout = $_SESSION['murrix']['path'];
		return true;
	}
}

?>