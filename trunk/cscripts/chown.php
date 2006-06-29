<?

class csChown extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		if (!empty($args))
		{
			$args_split = splitArgs($args);
			list($ug, $path, $recursive) = $args_split;
			
			list($username, $groupname) = explode(".", $ug);
			
			if ($path{0} != "/")
				$path = $_SESSION['murrix']['path']."/$path";
				
			$node_id = getNode($path);
			
			if ($node_id <= 0)
			{
				$stderr = ucf(i18n("no such path"))." $path";
				return true;
			}
			else
				$object = new mObject($node_id);
			
			$user = new mUser();
			$user->setByUsername($username);
			
			if ($user->id <= 0)
			{
				$stderr = ucf(i18n("no such user"));
				return true;
			}
			
			$group_id = 0;
			if (!empty($groupname))
			{
				$group = new mGroup();
				$group->setByName($groupname);
				
				if ($group->id <= 0)
				{
					$stderr = ucf(i18n("no such group"));
					return true;
				}
				$group_id = $group->id;
			}
			
			if (!(isAdmin() || $object->hasRight("write")))
			{
				$stderr .= ucf(i18n("not enough rights to change ownership on"))." ".$object->getPathInTree();
			}
			else
			{
				if ($recursive == "-r" || $recursive == "-R")
				{
					$stderr = $this->setOwnerOnObjectsRecursive($object, $stdout, $stderr, $user->id, $group_id);
				}
				else
				{
					$object->setUserId($user->id);
				
					if ($group_id > 0)
						$object->setGroupId($group_id);
					
					if ($object->saveCurrent())
						$stdout = "";//ucf(i18n("changed ownership successfully on"))." ".$object->getPathInTree();
					else
						$stderr = ucf(i18n("failed to change ownership on"))." ".$object->getPathInTree();
				}
				
				
			}
		}
		else
		{
			$stdout = "Usage: chown [username].[groupname] [path] [-R]\n";
			$stdout .= "Example: chown admin.admins /root";
		}
		
		return true;
	}
	
	function setOwnerOnObjectsRecursive(&$object, &$stdout, &$stderr, $user_id, $group_id = 0)
	{
		if (!(isAdmin() || $object->hasRight("write")))
			$stderr .=  ucf(i18n("not enough rights to change ownership on"))." ".$object->getPathInTree()."\n";
	
		$object->setUserId($user_id);
				
		if ($group_id > 0)
			$object->setGroupId($group_id);
			
		if ($object->saveCurrent())
			$stdout .= "";//ucf(i18n("changed ownership successfully on"))." ".$object->getPathInTree()."\n";
		else
			$stderr .= ucf(i18n("failed to change ownership on"))." ".$object->getPathInTree()."\n";
			
		$children = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' NODESORTBY property:version SORTBY property:name");
	
		for ($n = 0; $n < count($children); $n++)
			$this->setOwnerOnObjectsRecursive($children[$n], $stdout, $stderr, $user_id, $group_id);
	}
}

?>