<?
echo externcmd(img(geticon("global"))." ".ucf(i18n("external link here")), $_SESSION['murrix']['lastcmd'], "externlink");

$current_view = "links";
include(gettpl("adminpanel", $object));

$left = img(geticon("link"))."&nbsp;".ucf(i18n("links"));
$right = $center = "";
include(gettpl("big_title"));

if ($object->hasRight("edit"))
{
?>
	<div class="main">
		<form id="linkForm" name="linkForm" action="javascript:void(null);" onsubmit="Post('links', 'zone_main', 'linkForm')">
			<?=ucf(i18n("create new link to "))?>
			<input name="action" class="hidden" type="hidden" value="newlink"/>
			<input name="node_id" class="hidden" type="hidden" value="<?=$object->getNodeId()?>"/>
			<input name="path" class="input_big" type="text" value="/Root"/>
			<?=i18n("as")?>
			<select class="form" name="type">
			<?
				$link_types = array("sub" => "child");
				foreach ($link_types as $key => $link_type)
				{
					echo "<option ".($key == "sub" ? "selected" : "")." value=\"$key\">".ucw(str_replace("_", " ", $link_type))."</option>";
				}
			?>
			</select>
			<input class="submit" type="submit" value="<?=ucf(i18n("link"))?>"/>
		</form>
	</div>
<?
}

$links = $object->getLinks();

$linklist[] = array(ucfirst(i18n("type")), ucfirst(i18n("remote node")), ucfirst(i18n("remote node is on"))."...", "&nbsp;");
foreach ($links as $link)
{
	if ($link['remote_id'] <= 0)
		$remote = ucf(i18n("unknown"));
	else
	{
		$remote_obj = new mObject($link['remote_id']);
		$remote = cmd(img(geticon($remote_obj->getIcon()))."&nbsp;".$remote_obj->getName(), "Exec('show','zone_main', Hash('path', '".$remote_obj->getPath()."'))");
	}

	if ($object->hasRight("delete"))
		$delete = cmd(img(geticon("delete"))."&nbsp;".ucf(i18n("delete")), "Exec('links','zone_main', Hash('action', 'deletelink', 'node_id', '".$object->getNodeId()."', 'remote_id', '".$link['remote_id']."', 'type', '".$link['type']."', 'direction', '".($link['direction'] == "top" ? "bottom" : "top")."'))");
	else
		$delete = "";

	$linklist[] = array($link['type'], $remote, ucf(i18n($link['direction'])), $delete);
}

table($linklist, "% ".i18n("rows"));

?>