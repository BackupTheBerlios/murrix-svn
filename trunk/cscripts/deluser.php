<?

class csDeluser extends CScript
{
	function csDeluser()
	{
		$this->stage = 0;
	}
	
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		if (!isAdmin())
		{
			$stderr = ucf(i18n("not enough rights to delete user"));
			return true;
		}
		
		switch ($this->stage)
		{
			case 1:
				if (empty($args) || strtolower($args) == "y" || strtolower($args) == "yes")
				{
					$result = delUser($this->username);
					
					if ($result === true)
						$stdout = ucf(i18n("removed user successfully"));
					else
						$stdout = $result;
						
					$this->stage = 0;
					return true;
				}
				
				$stdout = ucf(i18n("aborted by user"));
				$this->stage = 0;
				return true;
		}
		
		if (empty($args))
		{
			$stdout = ucf(i18n("you must specifiy a username"));
			return true;
		}
		else
		{
			$this->username = $args;
			$stdout = ucf(i18n("are you sure you want to delete this user"))." (Y/n)?";
			$this->stage = 1;
		}
		
		
		return false;
	}
}

?>