<?

class csLicense extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
	{
		$stdout = "<a target=\"top\" href=\"./docs/LICENSE.txt\">GNU General Public License</a>";;
		return true;
	}
}

?>