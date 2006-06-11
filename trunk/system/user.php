<?

function changePassword($user_node_id, $password)
{
	$user = new mObject($user_node_id);
	if (($user_node_id == $_SESSION['murrix']['user']->getNodeId() || $user->HasRight("edit")) && !isAnonymous())
	{
		$user->setVarValue("password", md5($password));
	
		if (!$user->save())
		{
			$message = "Operation unsuccessfull.\n";
			$message .= "Error output:\n";
			$message .= $user->getLastError();
			return $message;
		}
		
		return true;
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

	$user = fetch("FETCH object WHERE property:class_name='user' AND var:username='$username'");
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

	$group = fetch("FETCH object WHERE property:class_name='group' AND var:name='$name'");
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