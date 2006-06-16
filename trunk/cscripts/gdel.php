<?

class csGdel extends CScript
{
	function csGdel()
	{
		$this->stage = 0;
	}
	
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		if (!isAdmin())
		{
			$stderr = ucf(i18n("not enough rights to delete group"));
			return true;
		}
		
		switch ($this->stage)
		{
			case 1:
				if (empty($args) || strtolower($args) == "y" || strtolower($args) == "yes")
				{
					$result = delGroup($this->name);
					
					if ($result === true)
						$stdout = ucf(i18n("removed group successfully"));
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
			$stdout = "Usage: gdel [username]\n";
			$stdout .= "Example: gdel admin";
			return true;
		}
		else
		{
			$this->name = $args;
			$stdout = ucf(i18n("are you sure you want to delete this group"))." (Y/n)?";
			$this->stage = 1;
		}
		
		
		return false;
	}
}

?>