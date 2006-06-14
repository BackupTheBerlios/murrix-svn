<?

class csLinks extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$object = new mObject(getNode($_SESSION['murrix']['path']));
		
		$links = $object->getLinks();
		
		$stdout .= "total ".count($links)."\n";
		if (count($links) > 0)
		{
			$stdout .= "<table cellspacing=\"0\">";
			$stdout .= "<tr class=\"table_title\">";
			$stdout .= "<td>Id</td>";
			$stdout .= "<td>Type</td>";
			$stdout .= "<td>Remote node</td>";
			$stdout .= "<td>Remote node is on...</td>";
			$stdout .= "</tr>";
			foreach ($links as $link)
			{
				if ($link['remote_id'] <= 0)
					$remote = ucf(i18n("unknown"));
				else
				{
					$remote_obj = new mObject($link['remote_id']);
					$remote = cmd(img(geticon($remote_obj->getIcon()))."&nbsp;".$remote_obj->getName(), "Exec('show','zone_main',Hash('node_id','".$remote_obj->getNodeId()."'))");
				}

				$stdout .= "<tr>";
				$stdout .= "<td>".$link['id']."</td>";
				$stdout .= "<td>".$link['type']."</td>";
				$stdout .= "<td>".$remote."</td>";
				$stdout .= "<td>".ucf(i18n($link['direction']))."</td>";
				$stdout .= "</tr>";
			}
			$stdout .= "</table>";
		}
		return true;
	}
}

?>