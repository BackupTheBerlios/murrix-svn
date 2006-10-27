<?

class csChmod extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
	{
		if (!empty($args))
		{
			list($rights, $path) = explode(" ", $args, 2);
			
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
			
			$object->setRights($rights);
		
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