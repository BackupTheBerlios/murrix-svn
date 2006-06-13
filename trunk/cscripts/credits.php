<?

class csCredits extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$stdout = "Björn Ramberg\nFredrik Möllerstrand\nRickard Avellan";
		return true;
	}
}

?>