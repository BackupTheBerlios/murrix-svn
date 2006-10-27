<?

class csMlist extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
	{
		$metadata = getInitialMetadata();
		
		if ($args == "-l")
		{
			$stdout .= "total ".count($metadata)."\n";
			if (count($metadata) > 0)
			{
				$stdout .= "<table cellspacing=\"0\">";
				$stdout .= "<tr class=\"table_title\">";
				$stdout .= "<td>Class</td>";
				$stdout .= "<td>Name</td>";
				$stdout .= "<td>Value</td>";
				$stdout .= "</tr>";
				foreach ($metadata as $meta)
				{
					$stdout .= "<tr>";
					$stdout .= "<td>".$meta['class_name']."</td>";
					$stdout .= "<td>".$meta['name']."</td>";
					$stdout .= "<td>".$meta['value']."</td>";
					$stdout .= "</tr>";
				}
				$stdout .= "</table>";
			}
		}
		else
		{
			foreach ($metadata as $meta)
				$stdout .= $meta['name']." ";
		}
		
		return true;
	}
}

?>