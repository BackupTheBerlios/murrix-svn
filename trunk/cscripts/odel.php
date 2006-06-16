<?

class csOdel extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		if (!empty($args))
		{
			$path = $args;
			
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
				$stderr = ucf(i18n("not enough rights to delete"));
				return true;
			}
			
			clearNodeFileCache($object->getNodeId());
			$object->deleteNode();
			$stdout = ucf(i18n("deleted node successfully"));
		}
		else
		{
			$stdout = "Usage: odel [name]\n";
			$stdout .= "Example: odel oldfolder";
		}
		
		return true;
	}
}

?>