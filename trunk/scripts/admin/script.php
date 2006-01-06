<?

class sAdmin extends Script
{
	function sAdmin()
	{
	}
	
	function Exec(&$system, &$response, $args)
	{
		if (isset($args['action']))
		{
			if ($args['action'] == "createuser")
			{
				$users = new mObject(resolvePath("/Root/Users"));
				if (!$users->HasRight("create_subnodes"))
				{
					$this->Draw($system, $response, array("show" => "default"));
					return;
				}
				
				$username = trim($args['username']);
				if (empty($username))
				{
					$response->addAlert(ucf(i18n("you must enter a username")));
					return;
				}
				
				$name = trim($args['name']);
				if (empty($name))
				{
					$response->addAlert(ucf(i18n("you must enter a name")));
					return;
				}

				$password1 = trim($args['password1']);
				$password2 = trim($args['password2']);
				if (empty($password1) || empty($password2))
				{
					$response->addAlert(ucf(i18n("you must enter the password twice")));
					return;
				}
				else if ($password1 != $password2)
				{
					$response->addAlert(ucf(i18n("password do not match password confirmation")));
					return;
				}

				$user = fetch("FETCH object WHERE property:class_name='user' AND var:username='$username'");
				if (count($user) > 0)
				{
					$response->addAlert(ucf(i18n("a user with this username already exists, please choose another username")));
					return;
				}
				
				$group = fetch("FETCH object WHERE property:class_name='group' AND var:name='$name'");
				if (count($group) > 0)
				{
					$response->addAlert(ucf(i18n("a group with this name already exists, please choose another name")));
					return;
				}

				$group = new mObject();
				$group->setClassName("group");
				$group->loadVars();

				$group->name = $name;
				$group->language = "eng";

				$group->setVarValue("description", "This is the group of $name");

				if ($group->save())
					$group->linkWithNode($users->getNodeId());
				else
				{
					$message = "Operation unsuccessfull.<br/>";
					$message .= "Error output:<br/>";
					$message .= $group->getLastError();
					$response->addAlert($message);
					return;
				}

				$user = new mObject();
				$user->setClassName("user");
				$user->loadVars();

				$user->name = $name;
				$user->language = "eng";

				$user->setVarValue("username", $username);
				$user->setVarValue("password", md5($password1));

				if ($user->save())
				{
					$user->linkWithNode($group->getNodeId());
					$user->linkWithNode(resolvePath("/Root/Users/Anonymous"));
				}
				else
				{
					$message = "Operation unsuccessfull.<br/>";
					$message .= "Error output:<br/>";
					$message .= $user->getLastError();
					$response->addAlert($message);
					return;
				}

				$home_path = "/Root/Home/".$name;
				$home_id = resolvePath($home_path);
				if ($home_id < 0)
				{
					$home_folder = new mObject();
					$home_folder->setClassName("folder");
					$home_folder->loadVars();
	
					$home_folder->name = $name;
					$home_folder->language = "eng";
	
					$home_folder->setVarValue("description", "This is the home folder of $name");
	
					if ($home_folder->save())
						$home_folder->linkWithNode(resolvePath("/Root/Home"));
					else
					{
						$message = "Operation unsuccessfull.<br/>";
						$message .= "Error output:<br/>";
						$message .= $home_folder->getLastError();
						$response->addAlert($message);
						return;
					}
				}

				$message = $this->GiveRightsOnHome($group, $home_path);

				if (!empty($message))
				{
					$response->addAlert($message);
					return;
				}
				
				$this->Draw($system, $response, array("show" => "start"));
				return;
			}
			else if ($args['action'] == "creategroup")
			{
				$users = new mObject(resolvePath("/Root/Users"));
				if (!$users->HasRight("create_subnodes"))
				{
					$this->Draw($system, $response, array("show" => "default"));
					return;
				}

				$description = trim($args['description']);
				$name = trim($args['name']);
				if (empty($name))
				{
					$response->addAlert(ucf(i18n("you must enter a name")));
					return;
				}

				$group = fetch("FETCH object WHERE property:class_name='group' AND var:name='$name'");
				if (count($group) > 0)
				{
					$response->addAlert(ucf(i18n("a group with this name already exists, please choose another name")));
					return;
				}

				$group = new mObject();
				$group->setClassName("group");
				$group->loadVars();

				$group->name = $name;
				$group->language = "eng";

				if (!empty($description))
					$group->setVarValue("description", $description);

				if ($group->save())
					$group->linkWithNode($users->getNodeId());
				else
				{
					$message = "Operation unsuccessfull.<br/>";
					$message .= "Error output:<br/>";
					$message .= $group->getLastError();
					$response->addAlert($message);
					return;
				}

				$home_path = "/Root/Home/".$name;
				$home_id = resolvePath($home_path);
				if ($home_id < 0)
				{
					$home_folder = new mObject();
					$home_folder->setClassName("folder");
					$home_folder->loadVars();
	
					$home_folder->name = $name;
					$home_folder->language = "eng";
	
					$home_folder->setVarValue("description", "This is the home folder of $name");
	
					if ($home_folder->save())
						$home_folder->linkWithNode(resolvePath("/Root/Home"));
					else
					{
						$message = "Operation unsuccessfull.<br/>";
						$message .= "Error output:<br/>";
						$message .= $home_folder->getLastError();
						$response->addAlert($message);
						return;
					}
				}

				$message = $this->GiveRightsOnHome($group, $home_path);

				if (!empty($message))
				{
					$response->addAlert($message);
					return;
				}
				
				$this->Draw($system, $response, array("show" => "start"));
				return;
			}

		}
		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		if (!isset($args['show']))
			$args['show'] = "start";

		$users = new mObject(resolvePath("/Root/Users"));
		
		ob_start();

		if ($users->HasRight("create_subnodes"))
		{
			switch ($args['show'])
			{
				case "user_create":
					include(gettpl("scripts/admin/usercreate"));
					break;

				case "group_create":
					include(gettpl("scripts/admin/groupcreate"));
					break;
	
				case "start":
				default:
					include(gettpl("scripts/admin/start"));
					break;
			}
		}
		else
		{
			$titel = ucf(i18n("error"));
			$text = ucf(i18n("not enough rights"));
			include(gettpl("message"));
		}

		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
	}

	function GiveRightsOnHome($group, $home_path)
	{
		/*
		===================================
		== Right Read: Read Right Home ==
		===================================
		*/
		$read_home_obj = new mObject();
		$read_home_obj->setClassName("right_read");
		$read_home_obj->loadVars();
		$read_home_obj->setLanguage("eng");
		$read_home_obj->setName("Read Right Home");

		$read_home_obj->setVarValue("setting", "allow");
		$read_home_obj->setVarValue("path", $home_path);
		$read_home_obj->setVarValue("description", "This right gives read access");

		if ($read_home_obj->save())
			$read_home_obj->linkWithNode($group->getNodeId());
		else
			return "Failed to create ".$read_home_obj->getName();

		/*
		===================================
		== Right List: List Right Home ==
		===================================
		*/
		$list_home_obj = new mObject();
		$list_home_obj->setClassName("right_read_subnodes");
		$list_home_obj->loadVars();
		$list_home_obj->setLanguage("eng");
		$list_home_obj->setName("List Right Home");

		$list_home_obj->setVarValue("setting", "allow");
		$list_home_obj->setVarValue("path", $home_path);
		$list_home_obj->setVarValue("description", "This right gives list access");

		if ($list_home_obj->save())
			$list_home_obj->linkWithNode($group->getNodeId());
		else
			return "Failed to create ".$list_home_obj->getName();

		/*
		===================================
		== Right Create: Create Right Home ==
		===================================
		*/
		$create_home_obj = new mObject();
		$create_home_obj->setClassName("right_create_subnodes");
		$create_home_obj->loadVars();
		$create_home_obj->setLanguage("eng");
		$create_home_obj->setName("Create Right Home");

		$create_home_obj->setVarValue("path", $home_path);
		$create_home_obj->setVarValue("description", "This right gives rights to the creation of objects");

		if ($create_home_obj->save())
			$create_home_obj->linkWithNode($group->getNodeId());
		else
			return "Failed to create ".$create_home_obj->getName();

		/*
		===================================
		== Right Delete: Delete Right Home ==
		===================================
		*/
		$delete_home_obj = new mObject();
		$delete_home_obj->setClassName("right_delete");
		$delete_home_obj->loadVars();
		$delete_home_obj->setLanguage("eng");
		$delete_home_obj->setName("Delete Right Home");

		$delete_home_obj->setVarValue("setting", "allow");
		$delete_home_obj->setVarValue("path", $home_path);
		$delete_home_obj->setVarValue("description", "This right gives rights to delete");

		if ($delete_home_obj->save())
			$delete_home_obj->linkWithNode($group->getNodeId());
		else
			return "Failed to create ".$delete_home_obj->getName();

		/*
		===================================
		== Edit Delete: Edit Right Home ==
		===================================
		*/
		$edit_home_obj = new mObject();
		$edit_home_obj->setClassName("right_edit");
		$edit_home_obj->loadVars();
		$edit_home_obj->setLanguage("eng");
		$edit_home_obj->setName("Edit Right Home");

		$edit_home_obj->setVarValue("setting", "allow");
		$edit_home_obj->setVarValue("path", $home_path);
		$edit_home_obj->setVarValue("description", "This right gives edit rights");

		if ($edit_home_obj->save())
			$edit_home_obj->linkWithNode($group->getNodeId());
		else
			return "Failed to create ".$edit_home_obj->getName();

		return "";
	}
}
?>