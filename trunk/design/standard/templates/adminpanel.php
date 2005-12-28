<div class="adminpanel">
	<div class="tab<?=($current_view == "show" ? "_selected" : "")?>">
		<?=cmd(img(geticon("search"))."&nbsp;".ucf(i18n("view")), "Exec('show', 'zone_main', Hash('path', '".$object->getPath()."'));")?>
	</div>
	<?
	if ($object->hasRight("edit"))
	{
		?>
		<div class="tab<?=($current_view == "edit" ? "_selected" : "")?>">
			<?=cmd(img(geticon("edit"))."&nbsp;".ucf(i18n("edit")), "Exec('edit', 'zone_main', Hash('path', '".$object->getPath()."'));")?>
		</div>
	<?
	}
	?>
	<div class="tab<?=($current_view == "versions" ? "_selected" : "")?>">
		<?=cmd(img(geticon("list"))."&nbsp;".ucf(i18n("versions")), "Exec('versions', 'zone_main', Hash('path', '".$object->getPath()."'));")?>
	</div>
	<div class="tab<?=($current_view == "links" ? "_selected" : "")?>">
		<?=cmd(img(geticon("link"))."&nbsp;".ucf(i18n("links")), "Exec('links', 'zone_main', Hash('path', '".$object->getPath()."'));")?>
	</div>
	<?
	if ($object->hasRight("edit"))
	{
		?>
		<div class="tab<?=($current_view == "tools" ? "_selected" : "")?>">
			<?=cmd(img(geticon("settings"))."&nbsp;".ucf(i18n("tools")), "Exec('tools', 'zone_main', Hash('path', '".$object->getPath()."'));")?>
		</div>
		<?
	}
	if ($object->hasRight("delete"))
	{
		?>
		<div class="tab<?=($current_view == "delete" ? "_selected" : "")?>">
			<?=cmd(img(geticon("delete"))."&nbsp;".ucf(i18n("delete")), "Exec('delete', 'zone_main', Hash('path', '".$object->getPath()."'));")?>
		</div>
		<?
	}
	if ($object->hasRight("create_subnodes"))
	{
		?>
		<div class="tab<?=($current_view == "new" ? "_selected" : "")?>">
			<?=cmd(img(geticon("file"))."&nbsp;".ucf(i18n("new")), "Exec('new', 'zone_main', Hash('path', '".$object->getPath()."'));")?>
		</div>
		<div class="tab<?=($current_view == "upload" ? "_selected" : "")?>">
			<?=cmd(img(geticon("attach"))."&nbsp;".ucf(i18n("upload")), "Exec('upload', 'zone_main', Hash('path', '".$object->getPath()."'));")?>
		</div>
		<?
	}
	if ($object->hasRight("edit"))
	{
		?>
		<div class="tab<?=($current_view == "rights" ? "_selected" : "")?>">
			<?=cmd(img(geticon("right"))."&nbsp;".ucf(i18n("rights")), "Exec('rights', 'zone_main', Hash('path', '".$object->getPath()."'));")?>
		</div>
		<?
	}
	?>
	<div id="clear"></div>
</div>