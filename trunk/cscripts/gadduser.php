<?

class csGadduser extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
	{
		if (!isAdmin())
		{
			$stderr = ucf(i18n("not enough rights to add user to group"));
			return true;
		}
		
		if (empty($args))
		{
			$stdout = "Usage: gadduser [group] [username]\n";
			$stdout .= "Example: gadduser admins admin";
		}
		else
		{
			list($groupname, $username) = explode(" ", $args);
			$user = new mUser();
			$user->setByUsername($username);
			
			if ($user->id <= 0)
			{
				$stderr = ucf(i18n("no user named"))." $username ".i18n("found");
				return true;
			}
			
			$group = new mGroup();
			$group->setByName($groupname);
			
			if ($group->id <= 0)
			{
				$stderr = ucf(i18n("no group named"))." $groupname ".i18n("found");
				return true;
			}
			
			$user_groups = $user->getGroups();
			
			if (in_array($groupname, $user_groups))
			{
				$stderr = $username." ".i18n("is already a member of")." $groupname";
				return true;
			}
			
			$user->groups .= " $groupname";
			$user->save();
			
			$stdout = ucf(i18n("added"))." $username ".i18n("to")." $groupname";
		}
		
		return true;
	}
}

?>