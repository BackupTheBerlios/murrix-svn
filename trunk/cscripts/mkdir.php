<?

class csMkdir extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		if (!empty($args))
		{
			$path = $args;
			
			if ($path{0} != "/")
				$path = $_SESSION['murrix']['path']."/$path";
				
			$node_id = getNode($path);
			
			if ($node_id > 0)
			{
				$stderr = ucf(i18n("object already exists"));
				return true;
			}
			
			$parent = new mObject(getNode($_SESSION['murrix']['path']));
			
			if (!(isAdmin() || $parent->hasRight("create")))
			{
				$stderr = ucf(i18n("not enough rights to create folder"));
				return true;
			}
			
			$object = new mObject();
			$object->setClassName("folder");
			$object->loadVars();

			$object->name = basename($path);
			$object->language = $_SESSION['murrix']['language'];
			$object->rights = $parent->getMeta("initial_rights", "rwcrwc---");
			$object->group_id = $parent->getMeta("initial_group", $parent->getGroupId());

			if (!$object->save())
			{
				$stderr = "Operation unsuccessfull.\n";
				$stderr .= "Error output:\n";
				$stderr .= $object->getLastError();
				return true;
			}
			
			clearNodeFileCache($parent->getNodeId());
			$object->linkWithNode($parent->getNodeId());
			$stdout = ucf(i18n("created folder successfully"));
		}
			
		return true;
	}
}

?>