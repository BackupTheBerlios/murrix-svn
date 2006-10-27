<?

class csOinfo extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
	{
		$object = new mObject(getNode($_SESSION['murrix']['path']));

		$user = $object->getUser();
			
		$stdout .= "<table cellspacing=\"0\">";
		$stdout .= "<tr><td class=\"titlename\">Name</td><td>".cmd($object->getName(), "exec=show'&node_id=".$object->getNodeId())."</td></tr>";
		$stdout .= "<tr><td class=\"titlename\">Icon</td><td>".img(geticon($object->getIcon(), 16))."</td></tr>";
		$stdout .= "<tr><td class=\"titlename\">Id</td><td>".$object->getNodeId()."</td></tr>";
		$stdout .= "<tr><td class=\"titlename\">Revision</td><td>".$object->getVersion()."</td></tr>";
		$stdout .= "<tr><td class=\"titlename\">Language</td><td>".$object->getLanguage()."</td></tr>";
		$stdout .= "<tr><td class=\"titlename\">Class</td><td>".$object->getClassName()."</td></tr>";
		$stdout .= "<tr><td class=\"titlename\">Rights</td><td>".$object->getRights()."</td></tr>";
		$stdout .= "<tr><td class=\"titlename\">User</td><td>".$user->username."</td></tr>";
		$stdout .= "<tr><td class=\"titlename\">Time</td><td>".$object->getCreated()."</td></tr>";
		$stdout .= "</table>";
		
		return true;
	}
}

?>