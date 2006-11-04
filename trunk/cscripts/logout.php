<?

class csLogout extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
	{
		logout();

		$system->triggerEventIntern("logout", array());
		$stdout = ucf(i18n("logout successfull"));
		return true;
	}
}

?>