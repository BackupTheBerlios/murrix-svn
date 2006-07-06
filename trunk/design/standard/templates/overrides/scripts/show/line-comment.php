<?
$margin = empty($args['margin']) ? 0 : $args['margin'];
?>
<div class="show_line" style="margin-left: <?=($margin*30)?>px;">
	<div class="show_line_logo">
	<?
		$read_right = $object->hasRight("read");
		if ($read_right)
			echo cmd(img(geticon($object->getIcon(), 64)), "exec=show&node_id=".$object->getNodeId());
		else
			echo img(geticon($object->getIcon(), 64));
	?>
	</div>
	<div class="show_line_main_right">
	<?
		$admin = "";

		if ($object->hasRight("write"))
		{
			$admin .= "&nbsp;";
			$admin .= cmd(img(geticon("edit")), "exec=edit&node_id=".$object->getNodeId());

			$admin .= "&nbsp;";
			$admin .= cmd(img(geticon("delete")), "exec=delete&node_id=".$object->getNodeId());

			$admin .= "&nbsp;";
			$admin .= cmd(img(geticon("comment"))."&nbsp;".ucf(i18n("post answer")), "exec=new&node_id=".$object->getNodeId()."&class_name=comment");
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
						echo cmd($object->getName(), "exec=show&node_id=".$object->getNodeId());
					else
						echo $object->getName();
				?>
				</span>
				<?
				if ($read_right)
				{
					echo "- ".ucf(i18n("posted by"))." ";
					
					$user = $object->getUser();
					if ($user->id == 0)
						echo ucf(i18n("unknown"));
					else
						echo $user->name;
					
					echo " ".i18n("on")." ".date("Y-m-d H:i", strtotime($object->getCreated()));
				}
				?>
			</div>
		</div>

		<div class="show_line_main_bottom">
			<? if ($read_right) { echo $object->getVarShow("message"); } ?>
		</div>
	</div>

</div>
<div class="clear"></div>
<?
$children = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' AND property:class_name='comment' NODESORTBY property:version SORTBY property:created");

foreach ($children as $child)
	echo compiletpl("scripts/show/line", array("margin"=>$margin+1), $child);
?>