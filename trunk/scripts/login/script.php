<?

class sLogin extends Script
{
	function sLogin()
	{
	}

	function EventHandler(&$system, &$response, $event, $args = null)
	{
		switch ($event)
		{
			case "login":
			$this->Draw($system, $response, array('show' => "logout"));
			break;
			
			case "logout":
			$this->Draw($system, $response, array('show' => "login"));
			break;

			case "newlang":
			$this->Draw($system, $response, array());
			break;
		}
	}

	function Exec(&$system, &$response, $args)
	{
		if (isset($args['action']))
		{
			if ($args['action'] == "login")
			{
				$u = trim($args['username']);
				$p = md5(trim($args['password']));
		
				$user = fetch("FETCH object WHERE property:class_name='user' AND var:username='$u' AND var:password='$p'");
		
				if (count($user) == 0)
				{
					$response->addAlert(utf8e(ucf(i18n("login failed")).". ".ucf(i18n("please try again"))."."));
				}
				else
				{
					$_SESSION['murrix']['user'] = $user[0];

					unset($_SESSION['murrix']['querycache']['rights']);
					unset($_SESSION['murrix']['querycache']['rights_list']);
					
					$system->TriggerEventIntern($response, "login");
					$response->addScript("window.location.reload(false)");
				}
				return;
			}
			else if ($args['action'] == "logout")
			{
				global $anonymous_id;
	
				unset($_SESSION['murrix']['user']);
				$_SESSION['murrix']['user'] = new mObject($anonymous_id);

				unset($_SESSION['murrix']['querycache']['rights']);
				unset($_SESSION['murrix']['querycache']['rights_list']);
	
				$system->TriggerEventIntern($response, "logout");
				$response->addScript("window.location.reload(false)");
				return;
			}
		}
		
		$this->Draw($system, $response, array());
	}

	function Draw(&$system, &$response, $args)
	{
		ob_start();
		if (isset($args['show']))
		{
			if ($args['show'] == "logout")
				include(gettpl("scripts/login/logout"));
			else if ($args['show'] == "login")
				include(gettpl("scripts/login/login"));
		}
		else
		{
			if (IsAnonymous())
				include(gettpl("scripts/login/login"));
			else
				include(gettpl("scripts/login/logout"));
		}

		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
	}
}

?>