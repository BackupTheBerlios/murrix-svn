<?
$current_view = "tools";
include(gettpl("adminpanel", $object));

$right = $center = "";
$left = img(geticon("settings"))."&nbsp;".ucf(i18n("tools"));
include(gettpl("big_title", $object));

$children = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' NODESORTBY property:version SORTBY property:name");
$list = array();
$list[] = array(ucf(i18n("name")));

foreach ($children as $child)
{
	if ($child->getCreator() == 0)
		$creator = ucf(i18n("unknown"));
	else
	{
		$creator_obj = new mObject($child->getCreator());
		$creator = cmd($creator_obj->getName(), "Exec('show','zone_main',Hash('node_id','".$creator_obj->getNodeId()."'))");
	}

	$id = "node_id_".$child->getNodeId();

	$checkbox = "<input class=\"input\" type=\"checkbox\" id=\"$id\" name=\"node_ids[]\" value=\"".$child->getNodeId()."\"/>";

	$list[] = array("<a style=\"display: block;\" href=\"javascript:void(null)\" onclick=\"document.getElementById('$id').checked=!document.getElementById('$id').checked\">$checkbox&nbsp;".img(geticon($child->getIcon()))."&nbsp;".$child->getName()."</a>");
}

?>
<form id="toolsObjectList" name="toolsObjectList" action="javascript:void(null);" onsubmit="Post('tools', 'zone_main', 'toolsObjectList')">
	<? table($list, "% ".i18n("rows")) ?>
	
	<div class="main">
		<?=ucf(i18n("move selected objects to"))?>
		<input name="action" class="hidden" type="hidden" value="move"/>
		<input name="parent_id" class="hidden" type="hidden" value="<?=$object->getNodeId()?>"/>
		<input name="path" class="input_big" type="text" value="/Root"/>
		<a class="submit" href="javascript:void(null);" onclick="popWin=open('browse.php?input_path_id=path&form_id=toolsObjectList','PopUpWindow','width=300,height=300,scrollbars=1,status=0');popWin.opener=self; popWin.focus();popWin.moveTo(150,50);return false;"><?=ucf(i18n("browse"))?></a>

		<input class="submit" type="submit" value="<?=ucf(i18n("move"))?>"/>
		<input class="submit" type="submit" value="<?=ucf(i18n("link"))?>" onclick="document.getElementById('toolsObjectList').action.value='link';"/>
	</div>

</form>

