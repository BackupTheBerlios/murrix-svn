<?

class csLicense extends CScript
{
	function exec($stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$stdout = "<a target=\"top\" href=\"./docs/LICENSE.txt\">GNU General Public License</a>";;
		return true;
	}
}

?>