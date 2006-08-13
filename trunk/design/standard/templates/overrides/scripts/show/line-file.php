<div class="show_line">
	<div class="show_line_logo">
		<?=cmd(img(geticon($object->getIcon(), 64)), "exec=show&node_id=".$object->getNodeId())?>
	</div>
	<div class="show_line_main_right"></div>
	<div class="show_line_main">
		<div class="show_line_main_top">
			<div class="show_line_main_top_inner">
				<span class="show_line_main_top_inner_title">
					<?=cmd($object->getName(), "exec=show&node_id=".$object->getNodeId())?>
				</span>
			</div>
		</div>

		<div class="show_line_main_bottom">
		<?=DownloadSize(@filesize($object->getVarValue("file")))?>
		<a href="?file=<?=$object->resolveVarName("file")?>&download=1"><?=img(geticon("download"))." ".ucf(i18n("download"))?></a><br/>
		<?=$object->getVarShow("description")?>
		</div>
	</div>
</div>
<div class="clear"></div>