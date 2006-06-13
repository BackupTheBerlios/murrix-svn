<?

class csChown extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		if (!empty($args))
		{
			list($ug, $path) = explode(" ", $args, 2);
			
			list($username, $groupname) = explode(".", $ug);
			
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
				$stderr = ucf(i18n("not enough rights to change ownership"));
				return true;
			}
			
			$user = new mUser();
			$user->setByUsername($username);
			
			if ($user->id <= 0)
			{
				$stderr = ucf(i18n("no such user"));
				return true;
			}
			
			$object->setUserId($user->id);
			
			if (!empty($groupname))
			{
				$group = new mGroup();
				$group->setByName($groupname);
				
				if ($group->id <= 0)
				{
					$stderr = ucf(i18n("no such group"));
					return true;
				}
				
				$object->setGroupId($group->id);
			}
			
			if ($object->saveCurrent())
				$stdout = ucf(i18n("changed ownership successfully"));
			else
				$stderr = ucf(i18n("failed to change ownership"));
		}
			
		return true;
	}
}

?>