<?

class csCredits extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$stdout = "Bj�rn Ramberg\nFredrik M�llerstrand\nRickard Avellan";
		return true;
	}
}

?>