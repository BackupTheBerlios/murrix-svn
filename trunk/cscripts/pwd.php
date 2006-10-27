<?

class csPwd extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
	{
		$stdout = $_SESSION['murrix']['path'];
		return true;
	}
}

?>