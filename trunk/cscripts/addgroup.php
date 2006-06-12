<?

class csAddgroup extends CScript
{
	function csAddgroup()
	{
		$this->stage = 0;
	}
	
	function exec($stdin, &$stdout, &$stderr, &$response, &$system)
	{
		if (!isAdmin())
		{
			$stderr = ucf(i18n("not enough rights to create group"));
			return true;
		}
		
		switch ($this->stage)
		{
			case 1:
				$this->name = $stdin;
				$stdout = "$stdin\n";
				$stdout .= ucf(i18n("enter description:"));
				$this->stage = 2;
				return false;
				
			case 2:
				$this->description = $stdin;
				$stdout .= ucf(i18n("are you sure you want to create this group"))." (Y/n)?";
				$this->stage = 3;
				return false;
				
			case 3:
				if (empty($stdin) || strtolower($stdin) == "y" || strtolower($stdin) == "yes")
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
		
		if (empty($stdin))
		{
			$stdout = ucf(i18n("enter name:"));
			$this->stage = 1;
		}
		else
		{
			$this->name = $stdin;
			$stdout .= ucf(i18n("enter description:"));
			$this->stage = 2;
		}
		
		
		return false;
	}
}

?>