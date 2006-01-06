<div class="show_line">
	<div class="show_line_logo">
		<?=cmd(img(geticon($child->getIcon(), 64)), "Exec('show','zone_main', Hash('path', '".$child->getPathInTree()."'))")?>
	</div>
	<div class="show_line_logo_hidden"></div>
	<div class="show_line_main_right">
	<?
		$admin = "";

		if ($child->hasRight("edit"))
		{
			$admin .= cmd(img(geticon("edit")), "Exec('edit','zone_main', Hash('path', '".$child->getPathInTree()."'))");
		}

		if ($child->hasRight("delete"))
		{
			$admin .= "&nbsp;";
			$admin .= cmd(img(geticon("delete")), "Exec('delete','zone_main', Hash('path', '".$child->getPathInTree()."'))");
		}

		echo $admin;
	?>
	</div>
	<div class="show_line_main">
		<div class="show_line_main_top">
			<div class="show_line_main_top_inner">
				<span class="show_line_main_top_inner_title"><?=cmd($child->getName(), "Exec('show','zone_main', Hash('path', '".$child->getPathInTree()."'))")?></span>
			</div>
		</div>

		<div class="show_line_main_bottom">
			<?=$child->getVarValue("description")?>
		</div>
	</div>
	<div id="clear"></div>
</div>