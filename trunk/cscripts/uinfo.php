<?

class csUinfo extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
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
			
		if (!empty($user->created))
			$stdout .= "Created: ".$user->created."\n";
			
		if ($user->last_login == "0000-00-00 00:00:00")
			$stdout .= "Last login: Never"."\n";
		else
			$stdout .= "Last login: ".$user->last_login."\n";
			
		if ($user->last_active == "0000-00-00 00:00:00")
			$stdout .= "Last activity: Never";
		else
			$stdout .= "Last activity: ".$user->last_login;
		
		return true;
	}
}

?>