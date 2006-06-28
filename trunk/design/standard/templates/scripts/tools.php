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
	$id = "node_id_".$child->getNodeId();

	$checkbox = "<input class=\"input\" type=\"checkbox\" id=\"$id\" name=\"node_ids[]\" value=\"".$child->getNodeId()."\"/>";

	$list[] = array("<a style=\"display: block;\" href=\"javascript:void(null)\" onclick=\"document.getElementById('$id').checked=!document.getElementById('$id').checked\">$checkbox&nbsp;".img(geticon($child->getIcon()))."&nbsp;".$child->getName()."</a>");
}

?>
<form id="toolsObjectList" name="toolsObjectList" action="javascript:void(null);" onsubmit="Post('tools','toolsObjectList')">
	<? table($list, "% ".i18n("rows")) ?>
	
	<div class="main">
		<div class="container">
			<?=ucf(i18n("move selected objects to"))?>
			<input name="action" class="hidden" type="hidden" value="move"/>
			<input name="parent_id" class="hidden" type="hidden" value="<?=$object->getNodeId()?>"/>
			<input name="remote_node_id" id="remote_node_id" class="input" type="text" value=""/>
			<a href="javascript:void(null);" onclick="popWin=open('<?=gettpl_www("popups/nodebrowse")?>?input_id=remote_node_id&form_id=linkForm','PopUpWindow','width=300,height=300,scrollbars=1,status=0'); popWin.opener = self; popWin.focus(); popWin.moveTo(150,50); return false"><?=ucf(i18n("browse"))?></a>
	
			<input class="submit" type="submit" value="<?=ucf(i18n("move"))?>"/>
			<input class="submit" type="submit" value="<?=ucf(i18n("link"))?>" onclick="document.getElementById('toolsObjectList').action.value='link';"/>
		</div>
	</div>

</form>