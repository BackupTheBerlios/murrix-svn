<?

class csCredits extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
	{
		$stdout = "Bj�rn Ramberg\nFredrik M�llerstrand\nRickard Avellan";
		return true;
	}
}

?>