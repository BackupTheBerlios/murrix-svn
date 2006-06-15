<?
$current_view = "links";
include(gettpl("adminpanel", $object));

$left = img(geticon("link"))."&nbsp;".ucf(i18n("links"));
$right = $center = "";
include(gettpl("big_title"));

if ($object->hasRight("write"))
{
?>
	<div class="main">
		<form id="linkForm" name="linkForm" action="javascript:void(null);" onsubmit="Post('links','linkForm')">
			<?=ucf(i18n("create new link to"))?> 
			<input name="action" class="hidden" type="hidden" value="newlink"/>
			<input name="node_id" class="hidden" type="hidden" value="<?=$object->getNodeId()?>"/>
			<input name="path" class="input_big" type="text" value="/Root"/>

			<a class="submit" href="javascript:void(null);" onclick="popWin=open('browse.php?input_path_id=path&form_id=linkForm','PopUpWindow','width=300,height=300,scrollbars=1,status=0');popWin.opener=self; popWin.focus();popWin.moveTo(150,50);return false;"><?=ucf(i18n("browse"))?></a>
			
			<?=i18n("as")?>
			<select class="form" name="type">
			<?
				global $link_types;
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

$linklist[] = array(ucf(i18n("type")), ucf(i18n("remote node")), ucf(i18n("remote node is on"))."...", "&nbsp;");
foreach ($links as $link)
{
	if ($link['remote_id'] <= 0)
		$remote = ucf(i18n("unknown"));
	else
	{
		$remote_obj = new mObject($link['remote_id']);
		$remote = cmd(img(geticon($remote_obj->getIcon()))."&nbsp;".$remote_obj->getName(), "exec=show&node_id=".$remote_obj->getNodeId());
	}

	if ($object->hasRight("write"))
		$delete = cmd(img(geticon("delete"))."&nbsp;".ucf(i18n("delete")), "exec=links&action=deletelink&node_id=".$object->getNodeId()."&remote_id=".$link['remote_id']."&type=".$link['type']."&direction=".($link['direction'] == "top" ? "bottom" : "top"));
	else
		$delete = "";

	$linklist[] = array($link['type'], $remote, ucf(i18n($link['direction'])), $delete);
}

table($linklist, "% ".i18n("rows"));

?>