<?
$list = array();
$list[] = array(ucf(i18n("name")), ucf(i18n("description")));
for ($i = $args['start']; $i < $args['end']; $i++)
{
	$child = $args['objects'][$i];

	$read_right = $child->hasRight("read");
	if ($read_right)
	{
		$name = cmd(img(geticon($child->getIcon()))." ".$child->getName(), "exec=show&node_id=".$child->getNodeId());
		$description = $child->getVarValue("description");
	}
	else
	{
		$name = img(geticon($child->getIcon()))." ".$child->getName();
		$description = "";
	}

	$list[] = array($name, $description);
}

echo compiletpl("table", array("list"=>$list, "endstring"=>"% ".i18n("rows")), $object);
?>