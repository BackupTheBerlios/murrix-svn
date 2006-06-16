<?

class csGlist extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$group = new mGroup();
		$groups = $group->getList();
		
		if ($args == "-l")
		{
			$stdout .= "total ".count($groups)."\n";
			if (count($groups) > 0)
			{
				$stdout .= "<table cellspacing=\"0\">";
				$stdout .= "<tr class=\"table_title\">";
				$stdout .= "<td>Id</td>";
				$stdout .= "<td>Name</td>";
				$stdout .= "<td>Home</td>";
				$stdout .= "<td>Created</td>";
				$stdout .= "<td>Description</td>";
				$stdout .= "</tr>";
				foreach ($groups as $group)
				{
					$stdout .= "<tr>";
					$stdout .= "<td>".$group->id."</td>";
					$stdout .= "<td>".$group->name."</td>";
					
					if ($group->home_id > 0)
					{
						$home = new mObject($group->home_id);
						$stdout .= "<td>".cmd($home->getPath(), "exec=show&node_id=".$home->getNodeId())."</td>";
					}
					else
						$stdout .= "<td></td>";
					
					$stdout .= "<td>".$group->created."</td>";
					$stdout .= "<td>".$group->description."</td>";
					$stdout .= "</tr>";
				}
				$stdout .= "</table>";
			}
		}
		else
		{
			foreach ($groups as $group)
				$stdout .= $group->name." ";
		}
		
		return true;
	}
}

?>