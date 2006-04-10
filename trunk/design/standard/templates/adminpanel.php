<div class="adminpanel">
<?
	if ($object->hasRight("read"))
		echo cmd(img(geticon("search"))."&nbsp;".ucf(i18n("view")), "Exec('show','zone_main',Hash('node_id','".$object->getNodeId()."'))", ($current_view == "show" ? "tab_selected" : "tab"));
	
	if ($object->hasRight("edit"))
	{
		echo cmd(img(geticon("edit"))."&nbsp;".ucf(i18n("edit")), "Exec('edit','zone_main',Hash('node_id','".$object->getNodeId()."'))", ($current_view == "edit" ? "tab_selected" : "tab"));

		echo cmd(img(geticon("settings"))."&nbsp;".ucf(i18n("settings")), "Exec('settings','zone_main',Hash('node_id','".$object->getNodeId()."'))", ($current_view == "settings" ? "tab_selected" : "tab"));
	}

	if ($object->hasRight("read"))
	{
		echo cmd(img(geticon("list"))."&nbsp;".ucf(i18n("versions")), "Exec('versions','zone_main',Hash('node_id','".$object->getNodeId()."'))", ($current_view == "versions" ? "tab_selected" : "tab"));
	
		echo cmd(img(geticon("link"))."&nbsp;".ucf(i18n("links")), "Exec('links','zone_main',Hash('node_id','".$object->getNodeId()."'))", ($current_view == "links" ? "tab_selected" : "tab"));
	}
	
	if ($object->hasRight("edit"))
		echo cmd(img(geticon("settings"))."&nbsp;".ucf(i18n("tools")), "Exec('tools','zone_main',Hash('node_id','".$object->getNodeId()."'))", ($current_view == "tools" ? "tab_selected" : "tab"));
	
	if ($object->hasRight("delete"))
		echo cmd(img(geticon("delete"))."&nbsp;".ucf(i18n("delete")), "Exec('delete','zone_main',Hash('node_id','".$object->getNodeId()."'))", ($current_view == "delete" ? "tab_selected" : "tab"));

	if ($object->hasRight("create_subnodes"))
	{
		echo cmd(img(geticon("file"))."&nbsp;".ucf(i18n("new")), "Exec('new','zone_main',Hash('node_id','".$object->getNodeId()."'))", ($current_view == "new" ? "tab_selected" : "tab"));
	}

	if ($object->hasRight("create_subnodes", array("file", "file_folder")))
	{
		echo cmd(img(geticon("attach"))."&nbsp;".ucf(i18n("upload")), "Exec('upload','zone_main',Hash('node_id','".$object->getNodeId()."'))", ($current_view == "upload" ? "tab_selected" : "tab"));
	}
	
	//if ($object->hasRight("edit"))
	//	echo cmd(img(geticon("right"))."&nbsp;".ucf(i18n("rights")), "Exec('rights', 'zone_main', Hash('node_id', '".$object->getNodeId()."'));", ($current_view == "rights" ? "tab_selected" : "tab"));
	?>
</div>
<br/>
<div class="clear"></div>