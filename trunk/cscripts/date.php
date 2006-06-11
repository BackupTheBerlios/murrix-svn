<?

class csDate extends CScript
{
	function exec($stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$stdout = date("Y-m-d H:i:s");
		return true;
	}
}

?>