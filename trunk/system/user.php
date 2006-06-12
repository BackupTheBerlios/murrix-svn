<?

function login($username, $password)
{
	$user = new mUser();
	$password = md5($password);
	$users = $user->get("`username`='$username' AND `password`='$password'");
	
	if (count($users) == 0)
		return false;
		
	$_SESSION['murrix']['user'] = new mUser($users[0]['id']);
	return true;
}

function logout()
{
	global $anonymous_id;
	$_SESSION['murrix']['user'] = new mUser($anonymous_id);
	return true;
}

function changePassword($user_node_id, $password)
{
	$user = new mUser($user_node_id);
	if (($user_node_id == $_SESSION['murrix']['user']->id || $user->hasRight("write")) && !isAnonymous())
	{
		$user->password = md5($password);
		return $user->save();
	}
	
	return ucf(i18n("not enough rights to change password for \"".$user->getName()."\""));
}

function createUser($name, $username, $password)
{
	$users = new mObject(resolvePath("/Root/Users"));
	if (!$users->HasRight("create_subnodes"))
		return ucf(i18n("not enough rights to create new user"));

	if (empty($name))
		return ucf(i18n("a name must be specified"));
		
	if (empty($username))
		return ucf(i18n("a username must be specified"));

	$user = fetch("FETCH node WHERE property:class_name='user' AND var:username='$username' NODESORTBY property:version");
	if (count($user) > 0)
		return ucf(i18n("a user with that username already exists"));
		
		
	$group_id = createGroup($name, "This is the group of $name");

	$user = new mObject();
	$user->setClassName("user");
	$user->loadVars();

	$user->name = $name;
	$user->language = "eng";

	$user->setVarValue("username", $username);
	$user->setVarValue("password", md5($password));

	if ($user->save())
	{
		$user->linkWithNode($group_id);
		$user->linkWithNode(resolvePath("/Root/Users/Anonymous"));
	}
	else
	{
		$message = "Operation unsuccessfull.<br/>";
		$message .= "Error output:<br/>";
		$message .= $user->getLastError();
		return $message;
	}

	return $user->getNodeId();
}

function createGroup($name, $description, $language = "eng")
{
	$users = new mObject(resolvePath("/Root/Users"));
	if (!$users->HasRight("create_subnodes"))
		return ucf(i18n("not enough rights to create new group"));

	if (empty($name))
		return ucf(i18n("a name must be specified"));
	
	$group = fetch("FETCH node WHERE property:class_name='group' AND var:name='$name' NODESORTBY property:version");
	if (count($group) > 0)
		return ucf(i18n("a group with that name already exists"));

	$group = new mObject();
	$group->setClassName("group");
	$group->loadVars();

	$group->name = $name;
	$group->language = $language;

	if (!empty($description))
		$group->setVarValue("description", $description);

	if ($group->save())
		$group->linkWithNode($users->getNodeId());
	else
	{
		$message = "Operation unsuccessfull.<br/>";
		$message .= "Error output:<br/>";
		$message .= $group->getLastError();
		return $message;
	}

	return $group->getNodeId();
}

?>