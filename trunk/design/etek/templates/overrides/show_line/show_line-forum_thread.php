<?
if (!isset($com_count))
	$com_count = 0;
?>
<table id="line" style="margin-left: <?=($com_count*30)?>px;" cellspacing="0">
	<tr>
		<td id="left">
			<div id="main">
				<?=cmd(img(geticon($child->getIcon()))."&nbsp;".$child->getName(), "Exec('show','zone_main', Hash('path', '".$child->getPath()."'))", "titel")?>
				<br/>
				<?
				echo ucfirst(i18n("created by"))." ";
				if ($child->creator == 0)
					echo ucfirst(i18n("unknown"));
				else
				{
				
					$creator = new mObject($object->getCreator());

					if (!$creator->hasRight("read"))
						echo $creator->getName();
					else
						echo cmd($creator->getName(), "Exec('show','zone_main', Hash('path', '".$creator->getPath()."'))");
				}
				echo " ".i18n("on")." ".ucf(i18n(strtolower(date("l", strtotime($child->getCreated())))))." ".$child->getCreated();
				?>
			</div>
		</td>
		<td id="right">
			<div id="main">
				<?
				if ($child->hasRight("edit"))
				{
					echo cmd(img(geticon("edit")), "Exec('edit','zone_main', Hash('node_id', '".$child->getNodeId()."'))");
				}
				echo "<br/>";

				echo ucfirst(i18n("last post")).": unknown&nbsp;";
				if ($child->hasRight("delete"))
				{
					echo "&nbsp;";
					echo "<a onclick=\"Exec('delete','zone_main', Hash('node_id', '".$child->getNodeId()."'));\" href=\"javascript:void(null);\">";
					echo img(geticon("delete"));
					echo "</a>";
				}
				?>
				
			</div>
		</td>
	</tr>
</table>
<?
$com_data[$com_count]['children'] = fetch("FETCH node WHERE link:node_top='".$child->getNodeId()."' AND link:type='sub' AND property:class_name='comment' NODESORTBY property:version SORTBY property:created");

foreach ($com_data[$com_count]['children'] as $child)
{
	$com_count++;
	include(gettpl("show_line", $child));
	$com_count--;
}
?>
