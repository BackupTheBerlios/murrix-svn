<div class="adminpanel">
<?
	if ($users->HasRight("create_subnodes"))
	{
		echo cmd(img(geticon("search"))."&nbsp;".ucf(i18n("start")), "Exec('admin', 'zone_main', '')", ($current_view == "start" ? "tab_selected" : "tab"));
		
		echo cmd(img(geticon("user"))."&nbsp;".ucf(i18n("create new user")), "Exec('admin', 'zone_main', Hash('show', 'user_create'));", ($current_view == "user_create" ? "tab_selected" : "tab"));
	
		echo cmd(img(geticon("group"))."&nbsp;".ucf(i18n("create new group")), "Exec('admin', 'zone_main', Hash('show', 'group_create'));", ($current_view == "group_create" ? "tab_selected" : "tab"));
	}
	
	echo cmd(img(geticon("password"))."&nbsp;".ucf(i18n("change password")), "Exec('admin', 'zone_main', Hash('show', 'password_change'));", ($current_view == "password_change" ? "tab_selected" : "tab"));
		
	?>
	<div id="clear"></div>
</div>