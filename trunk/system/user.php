<?

function isAnonymous()
{
	global $anonymous_id;
	return ($_SESSION['murrix']['user']->id == $anonymous_id);
}

function isAdmin()
{
	$user_groups = $_SESSION['murrix']['user']->getGroups();
	return in_array("admins", $user_groups);
}

function login($username, $password)
{
	if (function_exists("overrideLogin"))
		return overrideLogin($username, $password);
		
	return realLogin($username, $password);
}

function realLogin($username, $password)
{
	if (empty($password))
		return false;
		
	$user = new mUser();
	$password = md5($password);
	$users = $user->get("`username`='$username' AND `password`='$password'");
	
	if (count($users) == 0)
		return false;
		
	$_SESSION['murrix']['user'] = new mUser($users[0]['id']);
	$_SESSION['murrix']['user']->last_login = date("Y-m-d H:i:s");
	$_SESSION['murrix']['user']->save();
	return true;
}

function logout()
{
	if (function_exists("overrideLogout"))
		return overrideLogout();
		
	return realLogout();
}

function realLogout()
{
	global $anonymous_id;
	$_SESSION['murrix']['user'] = new mUser($anonymous_id);
	return true;
}

function changePassword($user_node_id, $password)
{
	$user = new mUser($user_node_id);
	
	if (($user_node_id == $_SESSION['murrix']['user']->id || isAdmin()) && !isAnonymous())
	{
		$user->password = md5($password);
		return $user->save();
	}
	
	return ucf(i18n("not enough rights to change password for"))." \"".$user->name."\"";
}

function delUser($username)
{
	if (!isAdmin)
		return ucf(i18n("not enough rights to delete user"));

	$user = new mUser();
	$user->setByUsername($username);
	return $user->remove();
}

function createUser($name, $username, $password, $email, $groups, $create_home = true)
{
	if (!isAdmin)
		return ucf(i18n("not enough rights to create new user"));

	if (empty($name))
		return ucf(i18n("a name must be specified"));
		
	if (empty($username))
		return ucf(i18n("a username must be specified"));

	$user = new mUser();
	$user->setByUsername($username);

	if ($user->id > 0)
		return ucf(i18n("a user with that username already exists"));
		
	$group = new mGroup();
	$group->setByName($username);
	if ($group->id > 0)
		$group_id = $group->id;
	else
		$group_id = createGroup($username, "This is the group of $name", false);
		
	$group = new mGroup($group_id);
	$groups = $group->name." $groups";
	$groups = trim($groups);

	$user->name = $name;
	$user->username = $username;
	$user->password = md5($password);
	$user->groups = $groups;
	$user->email = $email;
	$ret = $user->save();
	
	if ($create_home && getNode("/root/home/users/".$username) <= 0)
	{
		$home = new mObject();
		$home->setClassName("folder");
		$home->loadVars();
	
		$home->name = $username;
		$home->language = $_SESSION['murrix']['language'];
		$home->rights = "$username=r";
	
		$home->setVarValue("description", "This is the home of $name");
	
		if ($home->save())
		{
			$home->setMeta("initial_rights", "$username=rwc");
			$home_folder = new mObject(getNode("/root/home/users"));
			$home->linkWithNode($home_folder->getNodeId());
		}
		else
		{
			$message = "Operation unsuccessfull.<br/>";
			$message .= "Error output:<br/>";
			$message .= $home->getLastError();
			return $message;
		}
		
		$user->home_id = $home->getNodeId();
		$user->save();
	}

	return $ret;
}

function delGroup($name)
{
	if (!isAdmin)
		return ucf(i18n("not enough rights to delete group"));
		
	$group = new mGroup();
	$group->setByName($name);
	return $group->remove();
}

function createGroup($name, $description, $create_home = true)
{
	if (!isAdmin)
		return ucf(i18n("not enough rights to create new group"));

	if (empty($name))
		return ucf(i18n("a name must be specified"));
	
	$group = new mGroup();
	$group->setByName($name);

	if ($group->id > 0)
		return ucf(i18n("a group with that name already exists"));

	$group->name = $name;
	$group->description = $description;
	$ret = $group->save();
	
	if ($create_home && getNode("/root/home/groups/".$name) <= 0)
	{
		$home = new mObject();
		$home->setClassName("folder");
		$home->loadVars();
	
		$home->name = $name;
		$home->language = $_SESSION['murrix']['language'];
		$home->rights = "$name=r";
	
		$home->setVarValue("description", "This is the home of $name");
	
		if ($home->save())
		{
			$home->setMeta("initial_rights", "$name=rwc");
			$home_folder = new mObject(getNode("/root/home/groups"));
			$home->linkWithNode($home_folder->getNodeId());
		}
		else
		{
			$message = "Operation unsuccessfull.<br/>";
			$message .= "Error output:<br/>";
			$message .= $home->getLastError();
			return $message;
		}
		
		$group->home_id = $home->getNodeId();
		$group->save();
	}
	
	return $ret;
}

?>