<?

class sLogin extends Script
{
	function sLogin()
	{
		$this->zone = "zone_login";
	}

	function EventHandler(&$system, &$response, $event, $args = null)
	{
		switch ($event)
		{
			case "login":
			case "logout":
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
				$p = trim($args['password']);
		
				if (!login($u, $p))
					$response->addAlert(utf8e(ucf(i18n("login failed")).". ".ucf(i18n("please try again"))."."));
				else
				{
					$system->TriggerEventIntern($response, "login", array());
					//$response->addScript("window.location.reload()");
				}
				return;
			}
			else if ($args['action'] == "logout")
			{
				logout();

				$system->TriggerEventIntern($response, "logout", array());
				//$response->addScript("window.location.reload()");
				return;
			}
		}
		
		$this->Draw($system, $response, array());
	}

	function Draw(&$system, &$response, $args)
	{
		ob_start();
		
		if (isAnonymous())
			include(gettpl("scripts/login/login"));
		else
			include(gettpl("scripts/login/logout"));

		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
	}
}

?>