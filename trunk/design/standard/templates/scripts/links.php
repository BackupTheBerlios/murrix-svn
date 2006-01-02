<?
$current_view = "links";
include(gettpl("adminpanel", $object));

$left = "<span style=\"font-weight: bold; font-size: 18px;\">".img(geticon("link"))."&nbsp;".ucf(i18n("links"))."</span>";
$right = $center = "";
include(gettpl("big_title"));

$links = $object->getLinks();

$linklist[] = array(ucfirst(i18n("type")), ucfirst(i18n("remote node")), ucfirst(i18n("remote node is on"))."...", "&nbsp;");
foreach ($links as $link)
{
	if ($link['remote_id'] == 0)
		$remote = ucf(i18n("unknown"));
	else
	{
		$remote_obj = new mObject($link['remote_id']);
		$remote = cmd(img(geticon($remote_obj->getIcon()))."&nbsp;".$remote_obj->getName(), "Exec('show','zone_main', Hash('path', '".$remote_obj->getPath()."'))");
	}

	if ($object->hasRight("delete"))
		$delete = cmd(img(geticon("delete"))."&nbsp;".ucf(i18n("delete")), "Exec('links','zone_main', Hash('action', 'deletelink', 'node_id', '".$object->getNodeId()."', 'remote_id', '".$link['remote_id']."', 'type', '".$link['type']."'))");
	else
		$delete = "";

	$linklist[] = array($link['type'], $remote, ucf(i18n($link['direction'])), $delete);
}
?>

<div style="margin-top: 5px">
	<? table($linklist, "% ".i18n("rows")) ?>
</div>