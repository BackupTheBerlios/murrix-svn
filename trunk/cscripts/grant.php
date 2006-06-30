<?

class csGrant extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		if (!empty($args))
		{
			$args_split = splitArgs($args);
			list($rights, $path, $recursive) = $args_split;
			
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
			
			if (!(isAdmin() || $object->hasRight("write")))
			{
				$stderr .= ucf(i18n("not enough rights to change ownership on"))." ".$object->getPathInTree();
			}
			else
			{
				$_SESSION['murrix']['objectcache']['disabled'] = true;
			
				if ($recursive == "-r" || $recursive == "-R")
				{
					$stderr = $this->setOwnerOnObjectsRecursive($object, $stdout, $stderr, $rights);
				}
				else
				{
					$org_rights_parts = explode(",", $object->getRights());
					list($name, $right) = explode("=", $rights);
					$new_rights = array();
					foreach ($org_rights_parts as $orp)
					{
						list($groupname, $grouprights) = explode("=", $orp);
						
						if ($name != $groupname)
							$new_rights[] = "$groupname=$grouprights";
					}
					if (!empty($right))
						$new_rights[] = "$name=$right";
						
					$object->setRights(implode(",", $new_rights));
					
					if ($object->saveCurrent())
						$stdout = "";//ucf(i18n("changed ownership successfully on"))." ".$object->getPathInTree();
					else
						$stderr = ucf(i18n("failed to change ownership on"))." ".$object->getPathInTree();
				}
				
				$_SESSION['murrix']['objectcache']['disabled'] = false;
			}
		}
		else
		{
			$stdout = "Usage: grant [groupname]=[rights] [path] [-R]\n";
			$stdout .= "Example: grant admins=rwc /root";
		}
		
		return true;
	}
	
	function setOwnerOnObjectsRecursive(&$object, &$stdout, &$stderr, $rights)
	{
		if (!(isAdmin() || $object->hasRight("write")))
			$stderr .=  ucf(i18n("not enough rights to change ownership on"))." ".$object->getPathInTree()."\n";
	
		$org_rights_parts = explode(",", $object->getRights());
		list($name, $right) = explode("=", $rights);
		$new_rights = array();
		foreach ($org_rights_parts as $orp)
		{
			list($groupname, $grouprights) = explode("=", $orp);
			
			if ($name != $groupname)
				$new_rights[] = "$groupname=$grouprights";
		}
		if (!empty($right))
			$new_rights[] = "$name=$right";
			
		$object->setRights(implode(",", $new_rights));
					
		if ($object->saveCurrent())
			$stdout .= "";//ucf(i18n("changed ownership successfully on"))." ".$object->getPathInTree()."\n";
		else
			$stderr .= ucf(i18n("failed to change ownership on"))." ".$object->getPathInTree()."\n";
			
		$children = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' NODESORTBY property:version SORTBY property:name");
	
		for ($n = 0; $n < count($children); $n++)
			$this->setOwnerOnObjectsRecursive($children[$n], $stdout, $stderr, $rights);
	}
	
}

?>