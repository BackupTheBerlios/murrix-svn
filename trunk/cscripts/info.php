<?

class csInfo extends CScript
{
	function exec($stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$object = new mObject(getNode($_SESSION['murrix']['path']));

		$creator = $object->getCreatorObj();
		if ($creator === false)
			$creator = "System";
		else
			$creator = $creator->getName();
			
		$stdout .= "<table cellspacing=\"0\">";
		$stdout .= "<tr><td class=\"titlename\">Name</td><td>".cmd($object->getName(), "Exec('show','zone_main',Hash('node_id','".$object->getNodeId()."'))")."</td></tr>";
		$stdout .= "<tr><td class=\"titlename\">Icon</td><td>".img(geticon($object->getIcon(), 16))."</td></tr>";
		$stdout .= "<tr><td class=\"titlename\">Id</td><td>".$object->getNodeId()."</td></tr>";
		$stdout .= "<tr><td class=\"titlename\">Revision</td><td>".$object->getVersion()."</td></tr>";
		$stdout .= "<tr><td class=\"titlename\">Language</td><td>".$object->getLanguage()."</td></tr>";
		$stdout .= "<tr><td class=\"titlename\">Class</td><td>".$object->getClassName()."</td></tr>";
		$stdout .= "<tr><td class=\"titlename\">Author</td><td>$creator</td></tr>";
		$stdout .= "<tr><td class=\"titlename\">Time</td><td>".$object->getCreated()."</td></tr>";
		$stdout .= "</table>";
		return true;
	}
}

?>