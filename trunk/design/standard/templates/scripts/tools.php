<?
echo compiletpl("scripts/show/tabs", array("view"=>"tools"), $object);
echo compiletpl("title/big", array("left"=>img(geticon("settings"))."&nbsp;".ucf(i18n("tools"))), $object);
?>
<form id="toolsObjectList" name="toolsObjectList" action="javascript:void(null);" onsubmit="Post('tools','toolsObjectList')">
<?
	$children = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' NODESORTBY property:version SORTBY property:name");
	
	$list = array();
	$list[] = array(ucf(i18n("name")));
	
	foreach ($children as $child)
	{
		$id = "node_id_".$child->getNodeId();
	
		$checkbox = "<input class=\"input\" type=\"checkbox\" id=\"$id\" name=\"node_ids[]\" value=\"".$child->getNodeId()."\"/>";
	
		$list[] = array("$checkbox&nbsp;<a href=\"javascript:void(null)\" onclick=\"document.getElementById('$id').checked=!document.getElementById('$id').checked\">".img(geticon($child->getIcon()))."&nbsp;".$child->getName()."</a>");
	}

	echo compiletpl("table", array("list"=>$list, "endstring"=>"% ".i18n("rows")), $object);
	?>
	<div class="main">
		<div class="container">
			<input class="submit" type="button" onclick="checkUncheckAll(this)" value="<?=ucf(i18n("invert selection"))?>"/>
			<input class="submit" type="submit" value="<?=ucf(i18n("delete"))?>" onclick="document.getElementById('toolsObjectList').action.value='delete';"/>
			<br/>
			<?=ucf(i18n("move selected objects to"))?>
			<input name="action" class="hidden" type="hidden" value="move"/>
			<input name="parent_id" class="hidden" type="hidden" value="<?=$object->getNodeId()?>"/>
			<input name="remote_node_id" id="remote_node_id" class="input" type="text" value=""/>
			<a href="javascript:void(null);" onclick="popWin=open('<?=gettpl_www("popups/nodebrowse")?>?input_id=remote_node_id&form_id=linkForm','PopUpWindow','width=300,height=300,scrollbars=1,status=0'); popWin.opener = self; popWin.focus(); popWin.moveTo(150,50); return false">[<?=ucf(i18n("browse"))?>]</a>
	
			<input class="submit" type="submit" value="<?=ucf(i18n("move"))?>"/>
			<input class="submit" type="submit" value="<?=ucf(i18n("create link"))?>" onclick="document.getElementById('toolsObjectList').action.value='link';"/>
		</div>
	</div>
</form>