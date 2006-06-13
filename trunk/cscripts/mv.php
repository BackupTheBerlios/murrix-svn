<?

class csMv extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		if (!empty($args))
		{
			$args_split = splitArgs($args);
			
			if (count($args_split) >= 2)
			{
				$source = $args_split[0];
				$target = $args_split[1];
			
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
				
				$parent_node_id = getNode($_SESSION['murrix']['path']);
				
				$source->linkWithNode($target->getNodeId(), "sub");
				$source->unlinkWithNode($parent_node_id, "sub", "bottom");
				clearNodeFileCache($source->getNodeId());
				clearNodeFileCache($target->getNodeId());
				clearNodeFileCache(getNode($_SESSION['murrix']['path']));
				
				$stdout = ucf(i18n("moved node successfully"));
			}
		}
			
		return true;
	}
}

?>