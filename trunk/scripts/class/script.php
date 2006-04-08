<?

class sClass extends Script
{
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
			else if ($args['action'] == "changepassword")
			{
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

				$user = $_SESSION['murrix']['user'];

				$user->setVarValue("password", md5($password1));

				if (!$user->save())
				{
					$message = "Operation unsuccessfull.<br/>";
					$message .= "Error output:<br/>";
					$message .= $user->getLastError();
					$response->addAlert($message);
					return;
				}

				$users = new mObject(resolvePath("/Root/Users"));
				if (!$users->HasRight("create_subnodes"))
				{
					$system->ExecIntern($response, "show", $this->zone);
					return;
				}
			}
		}
		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		if (!isset($args['show']))
			$args['show'] = "start";

		ob_start();

		switch ($args['show'])
		{
			case "show":
				include(gettpl("scripts/class/show"));
				break;

			case "list":
			default:
				include(gettpl("scripts/class/list"));
				break;
		}
		
/*		}
		else
		{
			$titel = ucf(i18n("error"));
			$text = ucf(i18n("not enough rights"));
			include(gettpl("message"));
		}
*/
		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
	}
}
?>