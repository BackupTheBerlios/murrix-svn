<?

class csClasses extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$classes = getClassList(true);
		
		$stdout .= "total ".count($classes)."\n";
		if (count($classes) > 0)
		{
			$stdout .= "<table cellspacing=\"0\">";
			$stdout .= "<tr class=\"table_title\">";
			$stdout .= "<td>Icon</td>";
			$stdout .= "<td>Name</td>";
			$stdout .= "</tr>";
			foreach ($classes as $class)
			{
				$stdout .= "<tr>";
				$stdout .= "<td>".img(geticon($class['default_icon']))."</td>";
				$stdout .= "<td>".$class['name']."</td>";
				$stdout .= "</tr>";
			}
			$stdout .= "</table>";
		}
		return true;
	}
}

?>