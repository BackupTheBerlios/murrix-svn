<?

class csLadd extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
	{
		if (!empty($args))
		{
			$args_split = splitArgs($args);
			
			if (count($args_split) >= 2)
			{
				$source = $args_split[0];
				$target = $args_split[1];
				
				$type = "sub";
				if (isset($args_split[2]))
					$type = $args_split[2];
			
				if ($source{0} != "/")
					$source = $_SESSION['murrix']['path']."/$source";
					
				$source_node_id = getNode($source);
				
				if ($source_node_id <= 0)
				{
					$stderr = ucf(i18n("no such path")).": $source";
					return true;
				}
				else
					$source = new mObject($source_node_id);
				
				if (!(isAdmin() || $source->hasRight("write")))
				{
					$stderr = ucf(i18n("not enough rights on source"));
					return true;
				}
				
				if ($target{0} != "/")
					$target = $_SESSION['murrix']['path']."/$target";
					
				$target_node_id = getNode($target);
				
				if ($target_node_id <= 0)
				{
					$stderr = ucf(i18n("no such path")).": $target";
					return true;
				}
				else
					$target = new mObject($target_node_id);
				
				if (!(isAdmin() || $target->hasRight("write")))
				{
					$stderr = ucf(i18n("not enough rights on target"));
					return true;
				}
				
				$source->linkWithNode($target->getNodeId(), $type);
				$stdout = ucf(i18n("linked nodes successfully"));
			}
		}
		else
		{
			$stdout = "Usage: ladd [sourcepath] [targetpath]\n";
			$stdout .= "Example: ladd \"/root/home\" \"/root/public\"";
		}
		
		return true;
	}
}

?>