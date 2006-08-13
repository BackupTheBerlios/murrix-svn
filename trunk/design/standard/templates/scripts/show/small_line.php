<?
	$name = htmlspecialchars($object->getName());
	if ($args['noicon'] != true)
		$name = img(geticon($object->getIcon()))."&nbsp;$name";
		
	echo cmd($name, "exec=show&node_id=".$object->getNodeId(), array("title" => htmlspecialchars($object->getName()), "class" => $args['class']));
?>