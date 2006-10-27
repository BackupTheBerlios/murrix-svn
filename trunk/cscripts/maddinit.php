<?

class csMaddinit extends CScript
{
	function csMaddinit()
	{
		$this->stage = 0;
	}
	
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
	{
		if (!isAdmin())
		{
			$stderr = ucf(i18n("not enough rights to set initial metadata"));
			return true;
		}
		
		if (empty($args))
		{
			$stdout = "Usage: maddinit [class name] [metadata name] [value]\n";
			$stdout .= "Example: maddinit file_folder view thumbnail";
		}
		else
		{
			list($class_name, $name, $value) = splitArgs($args);
			
			$return = setInitialMetadata($class_name, $name, $value);
			
			if ($return === true)
				$stdout = "Updated metadata successfully";
			else
				$stderr = $return;
		}
		
		
		return true;
	}
}

?>