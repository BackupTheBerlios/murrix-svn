<?

class csUsend extends CScript
{
	function csUsend()
	{
		$this->stage = 0;
	}
	
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
	{
		if (isAnonymous())
		{
			$stderr = ucf(i18n("anonymous user can not send messages"));
			return true;
		}
		
		switch ($this->stage)
		{
			case 1:
				$this->username = $args;
				$stdout = "$args\n";
				$stdout .= ucf(i18n("subject:"));
				$this->stage = 2;
				return false;
				
			case 2:
				$this->subject = $args;
				$stdout = "$args\n".ucf(i18n("message:"));
				$this->stage = 3;
				return false;
				
			case 3:
				$this->message = $args;
				$stdout = "$args\n".ucf(i18n("attachment:"));
				$this->stage = 4;
				return false;
			
			case 4:
				$this->attachment = $args;
				$stdout = "$args\n".ucf(i18n("are you sure you want to send message"))." (Y/n)?";
				$this->stage = 5;
				return false;
			
			case 5:
				if (empty($args) || strtolower($args) == "y" || strtolower($args) == "yes")
				{
					$user = new mUser();
					$user->setByUsername($this->username);
					
					if ($user->id <= 0)
					{
						$stderr = ucf(i18n("no such user"))." - ".$this->username;
						$this->stage = 0;
						return true;
					}
					
					if (!$user->sendMessage($this->subject, $this->message, $this->attachment))
					{
						$stderr = ucf(i18n("failed to send message"));
						$this->stage = 0;
						return true;
					}
					
					$stdout = ucf(i18n("sent message successfully"));
					$this->stage = 0;
					return true;
				}
				
				$stdout = ucf(i18n("aborted by user"));
				$this->stage = 0;
				return true;
		}
		
		if (empty($args))
		{
			$stdout = ucf(i18n("to:"));
			$this->stage = 1;
		}
		else
		{
			$this->username = $args;
			$stdout .= ucf(i18n("subject:"));
			$this->stage = 2;
		}
		
		
		return false;
	}
}

?>