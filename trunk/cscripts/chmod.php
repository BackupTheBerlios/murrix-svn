<?

class csChmod extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		if (!empty($args))
		{
			list($rights, $path) = explode(" ", $args, 2);
			
			list($type, $right) = explode("+", $rights);
			
			if ($path{0} != "/")
				$path = $_SESSION['murrix']['path']."/$path";
				
			$node_id = getNode($path);
			
			if ($node_id <= 0)
			{
				$stderr = ucf(i18n("no such path"));
				return true;
			}
			else
				$object = new mObject($node_id);
			
			if (!(isAdmin() || $object->hasRight("write")))
			{
				$stderr = ucf(i18n("not enough rights to change rights"));
				return true;
			}
			
			if (empty($right))
				$object->setRights($rights);
			else
			{
				switch ($type)
				{
					case "u":
						$rights = $object->getRights();
						$rights = substr_replace($rights, $right, 0, 3);
						$object->setRights($rights);
						break;
					
					case "g":
						$rights = $object->getRights();
						$rights = substr_replace($rights, $right, 3, 3);
						$object->setRights($rights);
						break;
						
					case "a":
						$rights = $object->getRights();
						$rights = substr_replace($rights, $right, 6, 3);
						$object->setRights($rights);
						break;
				}
			}
						
			if ($object->saveCurrent())
				$stdout = ucf(i18n("changed rights successfully"));
			else
				$stderr = ucf(i18n("failed to change rights"));
		}
		else
		{
			$stdout = "Usage: chmod [rightstring] [path]\n";
			$stdout .= "Example: chmod rwcrwcrwc /root";
		}
		
		return true;
	}
}

?>