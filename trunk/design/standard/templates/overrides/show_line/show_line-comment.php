<?
if (!isset($com_count))
	$com_count = 0;
?>

<div class="show_line" style="margin-left: <?=($com_count*30)?>px;">
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

		if ($child->hasRight("edit"))
		{
			$admin .= "&nbsp;";
			$admin .= cmd(img(geticon("edit")), "exec=edit&node_id=".$child->getNodeId());

			$admin .= "&nbsp;";
			$admin .= cmd(img(geticon("delete")), "exec=delete&node_id=".$child->getNodeId());
		}

		if ($child->hasRight("create"))
		{
			$admin .= "&nbsp;";
			$admin .= cmd(img(geticon("comment"))."&nbsp;".ucf(i18n("answer")), "exec=new&node_id=".$child->getNodeId()."','class_name','comment'))");
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
				<?
				if ($read_right)
				{
					echo "- ".ucf(i18n("posted by"))." ";
					
					$user = $child->getUser();
					if ($user->id == 0)
						echo ucf(i18n("unknown"));
					else
						echo $user->name;
					
					echo " ".i18n("on")." ".date("Y-m-d H:i", strtotime($child->getCreated()));
				}
				?>
			</div>
		</div>

		<div class="show_line_main_bottom">
			<? if ($read_right) { echo $child->getVarShow("message"); } ?>
		</div>
	</div>

</div>
<div id="clear"></div>
<?
$com_data[$com_count]['children'] = fetch("FETCH node WHERE link:node_top='".$child->getNodeId()."' AND link:type='sub' AND property:class_name='comment' NODESORTBY property:version SORTBY property:created");

foreach ($com_data[$com_count]['children'] as $child)
{
	$com_count++;
	include(gettpl("show_line", $child));
	$com_count--;
}
?>