<?
$name = $object->getName();
if ($args['noicon'] != true)
	$name = img(geticon($object->getIcon()))."&nbsp;$name";
	
echo cmd("<nobr>$name</nobr>", "exec=show&node_id=".$object->getNodeId(), array("title" => $object->getName(), "class" => $args['class']));
?>