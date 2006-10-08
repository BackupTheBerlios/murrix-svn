<?

class csCadd extends CScript
{

	function csCadd()
	{
		$this->stage = 0;
	}
	
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		if (!isAdmin())
		{
			$stderr = ucf(i18n("not enough rights to create class"));
			return true;
		}
		
		switch ($this->stage)
		{
			case 1:
				$this->name = $args;
				$stdout = "$args\n";
				$stdout .= ucf(i18n("enter icon:"));
				$this->stage = 2;
				return false;
				
			case 2:
				$this->icon = $args;
				$stdout = "$args\n";
				$stdout .= ucf(i18n("are you sure you want to create this class"))." (Y/n)?";
				$this->stage = 3;
				return false;
				
			case 3:
				if (empty($args) || strtolower($args) == "y" || strtolower($args) == "yes")
				{
					$result = createClass($this->name, $this->icon);
					
					if (is_numeric($result) || $result === true)
					{
						$stdout = ucf(i18n("created new class successfully"));
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
			$stdout .= ucf(i18n("enter icon:"));
			$this->stage = 2;
		}
		
		
		return false;
	}
}

?>