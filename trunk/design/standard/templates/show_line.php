<div class="show_line">
	<div class="show_line_logo">
	<?
		$read_right = $child->hasRight("read");
		if ($read_right)
			echo cmd(img(geticon($child->getIcon(), 64)), "exec=show&node_id=".$child->getNodeId());
		else
			echo img(geticon($child->getIcon(), 64));
	?>
	</div>
	<div class="show_line_logo_hidden"></div>
	<div class="show_line_main_right">
	<?
		$admin = "";

		if ($child->hasRight("write"))
		{
			$admin .= cmd(img(geticon("edit")), "exec=edit&node_id=".$child->getNodeId());
		}

		if ($child->hasRight("create"))
		{
			$admin .= "&nbsp;";
			$admin .= cmd(img(geticon("delete")), "exec=delete&node_id=".$child->getNodeId());
		}

		echo $admin;
	?>
	</div>
	<div class="show_line_main">
		<div class="show_line_main_top">
			<div class="show_line_main_top_inner">
				<span class="show_line_main_top_inner_title">
				<?
					if ($read_right)
						echo cmd($child->getName(), "exec=show&node_id=".$child->getNodeId());
					else
						echo $child->getName();
				?>
				</span>
			</div>
		</div>

		<div class="show_line_main_bottom">
			<? if ($read_right) { echo $child->getVarValue("description"); } ?>
		</div>
	</div>

</div>
<div class="clear"></div>