<?

class csLogout extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
	{
		logout();

		$system->TriggerEventIntern($response, "logout", array());
		$stdout = ucf(i18n("logout successfull"));
		return true;
	}
}

?>