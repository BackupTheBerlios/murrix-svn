<?

class csSearch extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$args = str_replace("*", "%", $args);
		
		$children = fetch("FETCH node WHERE property:name LIKE '%$args%' NODESORTBY property:version SORTBY property:name");
		$children = getReadable($children);
		
		$stdout .= "total ".count($children)."\n";
		if (count($children) > 0)
		{
			$stdout .= "<table cellspacing=\"0\">";
			$stdout .= "<tr class=\"table_title\">";
			$stdout .= "<td>Id</td>";
			$stdout .= "<td>Rev.</td>";
			$stdout .= "<td>Lang.</td>";
			$stdout .= "<td>Class</td>";
			$stdout .= "<td>Rights</td>";
			$stdout .= "<td>User</td>";
			$stdout .= "<td>Group</td>";
			$stdout .= "<td>Time</td>";
			$stdout .= "<td>Name</td>";
			$stdout .= "</tr>";
			foreach ($children as $child)
			{
				$user = $child->getUser();
				$group = $child->getGroup();
				
				$stdout .= "<tr>";
				$stdout .= "<td>".$child->getNodeId()."</td>";
				$stdout .= "<td>".$child->getVersion()."</td>";
				$stdout .= "<td>".$child->getLanguage()."</td>";
				$stdout .= "<td>".$child->getClassName()."</td>";
				$stdout .= "<td>".$child->getRights()."</td>";
				$stdout .= "<td>".$user->username."</td>";
				$stdout .= "<td>".$group->name."</td>";
				$stdout .= "<td>".$child->getCreated()."</td>";
				$stdout .= "<td>".cmd(img(geticon($child->getIcon(), 16))."&nbsp;".$child->getName(), "Exec('show','zone_main',Hash('node_id','".$child->getNodeId()."'))")."</td>";
				$stdout .= "</tr>";
			}
			$stdout .= "</table>";
		}
		return true;
	}
}

?>