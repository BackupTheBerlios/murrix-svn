<div class="show_line">
	<div class="show_line_logo">
	<?
		$read_right = $child->hasRight("read");
		if ($read_right)
			echo cmd(img(geticon($child->getIcon(), 64)), "Exec('show','zone_main',Hash('node_id','".$child->getNodeId()."'))");
		else
			echo img(geticon($child->getIcon(), 64));
	?>
	</div>
	<div class="show_line_main_right">
	<?
		echo date("Y-m-d H:i", strtotime($child->getCreated()));
		$admin = "";

		if ($child->hasRight("edit"))
		{
			$admin .= "&nbsp;";
			$admin .= cmd(img(geticon("edit")), "Exec('edit','zone_main',Hash('node_id','".$child->getNodeId()."'))");
		}

		if ($child->hasRight("delete"))
		{
			$admin .= "&nbsp;";
			$admin .= cmd(img(geticon("delete")), "Exec('delete','zone_main',Hash('node_id','".$child->getNodeId()."'))");
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
						echo cmd($child->getName(), "Exec('show','zone_main',Hash('node_id','".$child->getNodeId()."'))");
					else
						echo $child->getName();
				?>
				</span>
				
			</div>
		</div>

		<div class="show_line_main_bottom">
			<? if ($read_right) { echo $child->getVarValue("text"); } ?>
		</div>
	</div>

</div>
<div class="clear"></div>