<?

class csCredits extends CScript
{
	function exec($stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$stdout = "Björn Ramberg\nFredrik Möllerstrand\nRickard Avellan";
		return true;
	}
}

?>