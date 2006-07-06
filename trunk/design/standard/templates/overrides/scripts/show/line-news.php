<div class="show_line">
	<div class="show_line_logo">
		<?=cmd(img(geticon($object->getIcon(), 64)), "Exec('show','zone_main',Hash('node_id','".$object->getNodeId()."'))")?>
	</div>
	<div class="show_line_main_right">
		<?=date("Y-m-d H:i", strtotime($object->getCreated()))?>
	</div>
	<div class="show_line_main">
		<div class="show_line_main_top">
			<div class="show_line_main_top_inner">
				<span class="show_line_main_top_inner_title">
					<?=cmd($object->getName(), "Exec('show','zone_main',Hash('node_id','".$object->getNodeId()."'))")?>
				</span>
				
			</div>
		</div>

		<div class="show_line_main_bottom">
			<?=$object->getVarValue("text")?>
		</div>
	</div>
</div>
<div class="clear"></div>