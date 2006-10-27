<?

class csLogin extends CScript
{
	function csLogin()
	{
		$this->stage = 0;
	}
	
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
	{
		if (!empty($args))
		{
			switch ($this->stage)
			{
				case 1:
					if (!login($this->username, $args))
					{
						$stderr = ucf(i18n("login failed")).". ".ucf(i18n("please try again"));
					}
					else
					{
						$system->TriggerEventIntern($response, "login", array());
						//$response->addScript("window.location.reload()");
						
						$user = new mUser();
						$user->setByUsername($this->username);
						
						$stdout = $user->name." ".i18n("logged in successfully");
					}
					
					$this->stage = 0;
					return true;
			}
			
			$this->username = $args;
			$stdout = ucf(i18n("password:"));
			$this->stage = 1;
			$response->addAssign("cmdline","type","password");
			return false;
		}
		return true;
	}
}

?>