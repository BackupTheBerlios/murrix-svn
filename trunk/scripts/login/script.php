<?

class sLogin extends Script
{
	function sLogin()
	{
		$this->zone = "zone_login";
	}

	function eventHandler(&$system, $event, $args = null)
	{
		switch ($event)
		{
			case "login":
			case "logout":
			case "newlang":
			if ($this->active)
				$this->draw($system, array());
				
			break;
		}
	}

	function execute(&$system, $args)
	{
		if (isset($args['action']))
		{
			if ($args['action'] == "login")
			{
				$u = trim($args['username']);
				$p = trim($args['password']);
		
				if (!login($u, $p))
					$system->addAlert(utf8e(ucf(i18n("login failed")).". ".ucf(i18n("please try again"))."."));
				else
				{
					$system->triggerEventIntern("login", array());
					//$response->addScript("window.location.reload()");
				}
				return;
			}
			else if ($args['action'] == "logout")
			{
				logout();

				$system->triggerEventIntern("logout", array());
				//$response->addScript("window.location.reload()");
				return;
			}
		}
		
		$this->draw($system, array());
	}

	function draw(&$system, $args)
	{
		ob_start();
		
		if (isAnonymous())
			include(gettpl("scripts/login/login"));
		else
			include(gettpl("scripts/login/logout"));

		$system->setZoneData($this->zone, utf8e(ob_get_end()));
	}
}

?>