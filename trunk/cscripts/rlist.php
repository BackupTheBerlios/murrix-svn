<?

class csRlist extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$object = new mObject(getNode($_SESSION['murrix']['path']));
		$versions = fetch("FETCH object WHERE property:node_id='".$object->getNodeId()."' NODESORTBY property:language,property:version,property:name");
		
		$stdout .= "total ".count($versions)."\n";
		if (count($versions) > 0)
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
			foreach ($versions as $version)
			{
				$user = $version->getUser();
				$group = $version->getGroup();
				
				$stdout .= "<tr>";
				$stdout .= "<td>".$version->getId()."</td>";
				$stdout .= "<td>".$version->getVersion()."</td>";
				$stdout .= "<td>".$version->getLanguage()."</td>";
				$stdout .= "<td>".$version->getClassName()."</td>";
				$stdout .= "<td>".$version->getRights()."</td>";
				$stdout .= "<td>".$user->username."</td>";
				$stdout .= "<td>".$group->name."</td>";
				$stdout .= "<td>".$version->getCreated()."</td>";
				$stdout .= "<td>".img(geticon($version->getIcon(), 16))."&nbsp;".$version->getName()."</td>";
				$stdout .= "</tr>";
			}
			$stdout .= "</table>";
		}
		return true;
	}
}

?>