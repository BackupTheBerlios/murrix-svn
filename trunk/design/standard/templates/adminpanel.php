<?
$left = $right = "";
?>
<div style="margin-bottom: -4px;">
	<table class="invisible" cellspacing="0" cellpadding="0">
		<tr>
			<td>
			<?
				$center = "<span style=\"font-weight: bold;\">".img(geticon("search"))."&nbsp;".ucf(i18n("view"))."</span>";
				$selected = 0;
				if ($current_view != "show")
					$center = cmd($center, "Exec('show', 'zone_main', Hash('path', '".$object->getPath()."'));");
				else
					$selected = 1;
			
				include(gettpl("tab", $object));
			?>
			</td>
			<?
			if ($object->hasRight("edit"))
			{
				?>
				<td>
				<?
					$center = "<span style=\"font-weight: bold;\">".img(geticon("edit"))."&nbsp;".ucf(i18n("edit"))."</span>";
					$selected = 0;
					if ($current_view != "edit")
						$center = cmd($center, "Exec('edit', 'zone_main', Hash('path', '".$object->getPath()."'));");
					else
						$selected = 1;
					
					include(gettpl("tab", $object));
				?>
				</td>
			<?
			}
			?>
			<td>
			<?
				$center = "<span style=\"font-weight: bold;\">".img(geticon("list"))."&nbsp;".ucf(i18n("versions"))."</span>";
				$selected = 0;
				if ($current_view != "versions")
					$center = cmd($center, "Exec('versions', 'zone_main', Hash('path', '".$object->getPath()."'));");
				else
					$selected = 1;

				include(gettpl("tab", $object));
			?>
			</td>
			<td>
			<?
				$center = "<span style=\"font-weight: bold;\">".img(geticon("link"))."&nbsp;".ucf(i18n("links"))."</span>";
				$selected = 0;
				if ($current_view != "links")
					$center = cmd($center, "Exec('links', 'zone_main', Hash('path', '".$object->getPath()."'));");
				else
					$selected = 1;

				include(gettpl("tab", $object));
			?>
			</td>
			<?
			if ($object->hasRight("edit"))
			{
				?>
				<td>
				<?
					$center = "<span style=\"font-weight: bold;\">".img(geticon("settings"))."&nbsp;".ucf(i18n("tools"))."</span>";
					$selected = 0;
					if ($current_view != "operations")
						$center = cmd($center, "Exec('tools', 'zone_main', Hash('path', '".$object->getPath()."'));");
					else
						$selected = 1;
						
					include(gettpl("tab", $object));
				?>
				</td>
				<?
			}
			if ($object->hasRight("delete"))
			{
				?>
				<td>
				<?
					$center = "<span style=\"font-weight: bold;\">".img(geticon("delete"))."&nbsp;".ucf(i18n("delete"))."</span>";
					$selected = 0;
					if ($current_view != "delete")
						$center = cmd($center, "Exec('delete', 'zone_main', Hash('path', '".$object->getPath()."'));");
					else
   						$selected = 1;
						
					include(gettpl("tab", $object));
				?>
				</td>
				<?
			}
			if ($object->hasRight("create_subnodes"))
			{
				?>
				<td>
				<?
					$center = "<span style=\"font-weight: bold;\">".img(geticon("file"))."&nbsp;".ucf(i18n("new"))."</span>";
					$selected = 0;
					if ($current_view != "new")
						$center = cmd($center, "Exec('new', 'zone_main', Hash('path', '".$object->getPath()."'));");
					else
						$selected = 1;
						
					include(gettpl("tab", $object));
				?>
				</td>
				<td>
				<?
					$center = "<span style=\"font-weight: bold;\">".img(geticon("attach"))."&nbsp;".ucf(i18n("upload"))."</span>";
					$selected = 0;
					if ($current_view != "upload")
						$center = cmd($center, "Exec('upload', 'zone_main', Hash('path', '".$object->getPath()."'));");
					else
						$selected = 1;
						
					include(gettpl("tab", $object));
				?>
				</td>
				<?
			}
			if ($object->hasRight("edit"))
			{
				?>
				<td>
				<?
					$center = "<span style=\"font-weight: bold;\">".img(geticon("right"))."&nbsp;".ucf(i18n("rights"))."</span>";
					$selected = 0;
					if ($current_view != "rights")
						$center = cmd($center, "Exec('rights', 'zone_main', Hash('path', '".$object->getPath()."'));");
					else
						$selected = 1;
						
					include(gettpl("tab", $object));
				?>
				</td>
				<?
			}
			?>
		</tr>
	</table>
</div>