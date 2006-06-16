<?

class csClist extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$classes = getClassList(true);
		
		if ($args == "-l")
		{
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
		}
		else
		{
			foreach ($classes as $class)
				$stdout .= $class['name']." ";
		}
		
		return true;
	}
}

?>