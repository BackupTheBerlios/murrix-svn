<?

class csAddgroup extends CScript
{
	function csAddgroup()
	{
		$this->stage = 0;
	}
	
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		if (!isAdmin())
		{
			$stderr = ucf(i18n("not enough rights to create group"));
			return true;
		}
		
		switch ($this->stage)
		{
			case 1:
				$this->name = $args;
				$stdout = "$args\n";
				$stdout .= ucf(i18n("enter description:"));
				$this->stage = 2;
				return false;
				
			case 2:
				$this->description = $args;
				$stdout .= ucf(i18n("are you sure you want to create this group"))." (Y/n)?";
				$this->stage = 3;
				return false;
				
			case 3:
				if (empty($args) || strtolower($args) == "y" || strtolower($args) == "yes")
				{
					$result = createGroup($this->name, $this->description);
					
					if (is_numeric($result))
					{
						$stdout = ucf(i18n("created new group successfully"));
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
			$stdout = ucf(i18n("enter name:"));
			$this->stage = 1;
		}
		else
		{
			$this->name = $args;
			$stdout .= ucf(i18n("enter description:"));
			$this->stage = 2;
		}
		
		
		return false;
	}
}

?>