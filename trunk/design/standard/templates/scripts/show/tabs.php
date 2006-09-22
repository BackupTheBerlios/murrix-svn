<?
$text = "";

if (($object->hasRight("read") && ($object->getMeta("show_versionstab", 0) == 1 || $object->getMeta("show_linkstab", 0) == 1)) || $object->hasRight("edit"))
	$text .= cmd(img(geticon("search"))."&nbsp;".ucf(i18n("view")), "exec=show&node_id=".$object->getNodeId(), ($args['view'] == "show" ? "tab_selected" : "tab"));

if ($object->hasRight("edit"))
{
	$text .= cmd(img(geticon("edit"))."&nbsp;".ucf(i18n("edit")), "exec=edit&node_id=".$object->getNodeId(), ($args['view'] == "edit" ? "tab_selected" : "tab"));

	$text .= cmd(img(geticon("settings"))."&nbsp;".ucf(i18n("settings")), "exec=settings&node_id=".$object->getNodeId(), ($args['view'] == "settings" ? "tab_selected" : "tab"));
}

if ($object->hasRight("read"))
{
	if ($object->getMeta("show_versionstab", 0) == 1 || $object->hasRight("edit"))
		$text .= cmd(img(geticon("list"))."&nbsp;".ucf(i18n("versions")), "exec=versions&node_id=".$object->getNodeId(), ($args['view'] == "versions" ? "tab_selected" : "tab"));

	if ($object->getMeta("show_linkstab", 0) == 1 || $object->hasRight("edit"))
		$text .= cmd(img(geticon("link"))."&nbsp;".ucf(i18n("links")), "exec=links&node_id=".$object->getNodeId(), ($args['view'] == "links" ? "tab_selected" : "tab"));
}

if ($object->hasRight("write"))
{
	$text .= cmd(img(geticon("settings"))."&nbsp;".ucf(i18n("tools")), "exec=tools&node_id=".$object->getNodeId(), ($args['view'] == "tools" ? "tab_selected" : "tab"));
	$text .= cmd(img(geticon("delete"))."&nbsp;".ucf(i18n("delete")), "exec=delete&node_id=".$object->getNodeId(), ($args['view'] == "delete" ? "tab_selected" : "tab"));
}

if ($object->hasRight("create"))
{
	$text .= cmd(img(geticon("file"))."&nbsp;".ucf(i18n("new")), "exec=new&node_id=".$object->getNodeId(), ($args['view'] == "new" ? "tab_selected" : "tab"));
	
	$text .= cmd(img(geticon("attach"))."&nbsp;".ucf(i18n("upload")), "exec=upload&node_id=".$object->getNodeId(), ($args['view'] == "upload" ? "tab_selected" : "tab"));
}

if (!empty($text))
{
?>
	<div class="adminpanel_wrapper">
		<div class="adminpanel">
			<?=$text?>
			
		</div>
		<div class="clear"></div>
	</div>
	
<?
}
?>