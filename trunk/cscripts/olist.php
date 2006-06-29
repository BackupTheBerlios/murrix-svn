<?

class csOlist extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$object = new mObject(getNode($_SESSION['murrix']['path']));
		$children = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' NODESORTBY property:version SORTBY ".$object->getMeta("sort_by", "property:name"));
		
		if ($args == "-l")
		{
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
				$stdout .= "<td>Time</td>";
				$stdout .= "<td>Name</td>";
				$stdout .= "</tr>";
				foreach ($children as $child)
				{
					$user = $child->getUser();
					
					$stdout .= "<tr>";
					$stdout .= "<td>".$child->getNodeId()."</td>";
					$stdout .= "<td>".$child->getVersion()."</td>";
					$stdout .= "<td>".$child->getLanguage()."</td>";
					$stdout .= "<td>".$child->getClassName()."</td>";
					$stdout .= "<td>".$child->getRights()."</td>";
					$stdout .= "<td>".$user->username."</td>";
					$stdout .= "<td>".$child->getCreated()."</td>";
					$stdout .= "<td>".cmd(img(geticon($child->getIcon(), 16))."&nbsp;".$child->getName(), "exec=show&node_id=".$child->getNodeId())."</td>";
					$stdout .= "</tr>";
				}
				$stdout .= "</table>";
			}
		}
		else
		{
			foreach ($children as $child)
				$stdout .= $child->getName()." ";
		}
		
		return true;
	}
}

?>