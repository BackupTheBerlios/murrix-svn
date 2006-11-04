<div class="tabs">
	<?=cmd("XML", "exec=import&view=xml&node_id=".$object->getNodeId(), array("class" => "tab".($args['view'] == "xml" ? "_selected" : "")))?>
	<?=cmd(ucf(i18n("custom")), "exec=import&view=custom&node_id=".$object->getNodeId(), array("class" => "tab".($args['view'] == "custom" ? "_selected" : "")))?>
	<?=cmd(ucf(i18n("files")), "exec=import&view=files&node_id=".$object->getNodeId(), array("class" => "tab".($args['view'] == "files" ? "_selected" : "")))?>
	<?=cmd(ucf(i18n("upload folder")), "exec=import&view=upload&node_id=".$object->getNodeId(), array("class" => "tab".($args['view'] == "upload" ? "_selected" : "")))?>
	<br/>
	<div class="clear"></div>
</div> 
