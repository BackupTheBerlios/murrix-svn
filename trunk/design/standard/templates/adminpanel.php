<?
$adminpanel = "";

if (($object->hasRight("read") && ($object->getMeta("show_versionstab", 0) == 1 || $object->getMeta("show_linkstab", 0) == 1)) || $object->hasRight("edit"))
	$adminpanel .= cmd(img(geticon("search"))."&nbsp;".ucf(i18n("view")), "exec=show&node_id=".$object->getNodeId(), ($current_view == "show" ? "tab_selected" : "tab"));

if ($object->hasRight("edit"))
{
	$adminpanel .= cmd(img(geticon("edit"))."&nbsp;".ucf(i18n("edit")), "exec=edit&node_id=".$object->getNodeId(), ($current_view == "edit" ? "tab_selected" : "tab"));

	$adminpanel .= cmd(img(geticon("settings"))."&nbsp;".ucf(i18n("settings")), "exec=settings&node_id=".$object->getNodeId(), ($current_view == "settings" ? "tab_selected" : "tab"));
}

if ($object->hasRight("read"))
{
	if ($object->getMeta("show_versionstab", 0) == 1 || $object->hasRight("edit"))
		$adminpanel .= cmd(img(geticon("list"))."&nbsp;".ucf(i18n("versions")), "exec=versions&node_id=".$object->getNodeId(), ($current_view == "versions" ? "tab_selected" : "tab"));

	if ($object->getMeta("show_linkstab", 0) == 1 || $object->hasRight("edit"))
		$adminpanel .= cmd(img(geticon("link"))."&nbsp;".ucf(i18n("links")), "exec=links&node_id=".$object->getNodeId(), ($current_view == "links" ? "tab_selected" : "tab"));
}

if ($object->hasRight("write"))
	$adminpanel .= cmd(img(geticon("settings"))."&nbsp;".ucf(i18n("tools")), "exec=tools&node_id=".$object->getNodeId(), ($current_view == "tools" ? "tab_selected" : "tab"));

if ($object->hasRight("write"))
	$adminpanel .= cmd(img(geticon("delete"))."&nbsp;".ucf(i18n("delete")), "exec=delete&node_id=".$object->getNodeId(), ($current_view == "delete" ? "tab_selected" : "tab"));

if ($object->hasRight("create"))
{
	$adminpanel .= cmd(img(geticon("file"))."&nbsp;".ucf(i18n("new")), "exec=new&node_id=".$object->getNodeId(), ($current_view == "new" ? "tab_selected" : "tab"));
	
	$adminpanel .= cmd(img(geticon("attach"))."&nbsp;".ucf(i18n("upload")), "exec=upload&node_id=".$object->getNodeId(), ($current_view == "upload" ? "tab_selected" : "tab"));
}

if (!empty($adminpanel))
{
?>
	<div class="adminpanel">
		<?=$adminpanel?>
	</div>
	<br/>
	<div class="clear"></div>
<?
}
?>