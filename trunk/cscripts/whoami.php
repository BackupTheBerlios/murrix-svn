<?

class csWhoami extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		if (empty($args))
		{
			$user = $_SESSION['murrix']['user'];
		}
		else
		{
			$user = new mUser();
			$user->setByUsername($args);
			
			if ($user->id <= 0)
			{
				$stderr = ucf(i18n("no such user"));
				return true;
			}
		}
		
		$stdout = $user->name."\n";
		if (!empty($user->username))
			$stdout .= "Username: ".$user->username."\n";
			
		if (!empty($user->groups))
			$stdout .= "Groups: ".$user->groups."\n";
			
		if ($user->last_login == "0000-00-00 00:00:00")
			$stdout .= "Last login: Never";
		else
			$stdout .= "Last login: ".$user->last_login;
		
		return true;
	}
}

?>