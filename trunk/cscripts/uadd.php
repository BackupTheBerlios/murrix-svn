<?

class csUadd extends CScript
{
	function csUadd()
	{
		$this->stage = 0;
	}
	
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		if (!isAdmin())
		{
			$stderr = ucf(i18n("not enough rights to create user"));
			return true;
		}
	
		switch ($this->stage)
		{
			case 1:
				$this->username = $args;
				$stdout = "$args\n";
				$stdout .= ucf(i18n("enter name:"));
				$this->stage = 2;
				return false;
				
			case 2:
				$this->name = $args;
				$stdout = "$args\n".ucf(i18n("enter groups:"));
				$this->stage = 3;
				return false;
				
			case 3:
				$this->groups = $args;
				$stdout = "$args\n".ucf(i18n("create home"))." (Y/n)?";
				$this->stage = 4;
				return false;
				
			case 4:
				$this->create_home = (empty($args) || strtolower($args) == "y" || strtolower($args) == "yes");
				if ($this->create_home)
					$stdout = "yes\n";
				else
					$stdout = "no\n";
					
				$stdout .= ucf(i18n("are you sure you want to create this user"))." (Y/n)?";
				$this->stage = 5;
				return false;
				
			case 5:
				if (empty($args) || strtolower($args) == "y" || strtolower($args) == "yes")
				{
					$result = createUser($this->name, $this->username, "", $this->groups, $this->create_home);
					
					if (is_numeric($result))
					{
						$stdout = ucf(i18n("created new user successfully"));
						$this->stage = 0;
						return true;
					}
					
					$stderr = $result;
					$this->stage = 0;
					return true;
				}
				
				$stdout = ucf(i18n("aborted by user"));
				$this->stage = 0;
				return true;
		}
		
		if (empty($args))
		{
			$stdout = ucf(i18n("enter username:"));
			$this->stage = 1;
		}
		else
		{
			$this->username = $args;
			$stdout .= ucf(i18n("enter name:"));
			$this->stage = 2;
		}
		
		
		return false;
	}
}

?>