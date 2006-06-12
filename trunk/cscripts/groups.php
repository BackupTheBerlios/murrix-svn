<?

class csGroups extends CScript
{
	function exec($stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$group = new mGroup();
		
		$groups = $group->getList();
		$stdout .= "total ".count($groups)."\n";
		if (count($groups) > 0)
		{
			$stdout .= "<table cellspacing=\"0\">";
			$stdout .= "<tr class=\"table_title\">";
			$stdout .= "<td>Id</td>";
			$stdout .= "<td>Name</td>";
			$stdout .= "<td>Description</td>";
			$stdout .= "</tr>";
			foreach ($groups as $group)
			{
				$stdout .= "<tr>";
				$stdout .= "<td>".$group->id."</td>";
				$stdout .= "<td>".$group->name."</td>";
				$stdout .= "<td>".$group->description."</td>";
				$stdout .= "</tr>";
			}
			$stdout .= "</table>";
		}
		return true;
	}
}

?>