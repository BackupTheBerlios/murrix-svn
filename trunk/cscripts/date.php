<?

class csDate extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
	{
		$stdout = date("Y-m-d H:i:s");
		return true;
	}
}

?>