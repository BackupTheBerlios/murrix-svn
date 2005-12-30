<?
if (!isset($com_count))
	$com_count = 0;
?>

<div class="show_line" style="margin-left: <?=($com_count*30)?>px;">
	<div class="show_line_logo">
		<?=cmd(img(geticon($child->getIcon(), 64)), "Exec('show','zone_main', Hash('path', '".$child->getPath()."'))", "titel")?>
	</div>
	<div class="show_line_logo_hidden"></div>
	<div class="show_line_main_right">
	<?
		$admin = "";

		if ($child->hasRight("edit"))
		{
			$admin .= "&nbsp;";
			$admin .= cmd(img(geticon("edit")), "Exec('edit','zone_main', Hash('path', '".$child->getPath()."'))");
		}

		if ($child->hasRight("delete"))
		{
			$admin .= "&nbsp;";
			$admin .= cmd(img(geticon("delete")), "Exec('delete','zone_main', Hash('path', '".$child->getPath()."'))");
		}

		if ($child->hasRight("create_subnodes", array("comment")))
		{
			$admin .= "&nbsp;";
			$admin .= cmd(img(geticon("comment"))."&nbsp;".ucf(i18n("answer")), "Exec('new','zone_main', Hash('path', '".$child->getPath()."', 'class_name', 'comment'))");
		}

		echo $admin;
	?>
	</div>
	<div class="show_line_main">
		<div class="show_line_main_top">
			<div class="show_line_main_top_inner">
				<span class="show_line_main_top_inner_title"><?=cmd($child->getName(), "Exec('show','zone_main', Hash('path', '".$child->getPath()."'))", "titel")?></span> - <?=ucf(i18n("posted by"))?>
				<?
				if ($child->creator == 0)
					echo ucf(i18n("unknown"));
				else
				{
				
					$creator = new mObject($object->getCreator());

					if (!$creator->hasRight("read"))
						echo $creator->getName();
					else
						echo cmd($creator->getName(), "Exec('show','zone_main', Hash('path', '".$creator->getPath()."'))");
				}

				echo " ".i18n("on")." ".date("Y-m-d H:i", strtotime($child->getCreated()));
				?>
			</div>
		</div>

		<div class="show_line_main_bottom">
			<?=$child->getVarValue("message")?>
		</div>
	</div>
	<div id="clear"></div>
</div>

<?
$com_data[$com_count]['children'] = fetch("FETCH node WHERE link:node_top='".$child->getNodeId()."' AND link:type='sub' AND property:class_name='comment' NODESORTBY property:version SORTBY property:created");

foreach ($com_data[$com_count]['children'] as $child)
{
	$com_count++;
	include(gettpl("show_line", $child));
	$com_count--;
}
?>