<div class="adminpanel">
<?
	echo cmd(img(geticon("list"))."&nbsp;".ucf(i18n("list")), "Exec('class','zone_main','')", ($current_view == "list" ? "tab_selected" : "tab"));
	
	echo cmd(img(geticon("file"))."&nbsp;".ucf(i18n("create new class")), "Exec('class','zone_main',Hash('show','create'));", ($current_view == "create" ? "tab_selected" : "tab"));

?>
</div>
<br/>
<div class="clear"></div>