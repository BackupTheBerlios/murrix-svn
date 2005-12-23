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
				
				$admin = "";

				if ($child->hasRight("edit"))
				{
					$admin .= cmd(img(geticon("edit")), "Exec('edit','zone_main', Hash('path', '".$child->getPath()."'))");
				}
				
				if ($child->hasRight("delete"))
				{
					$admin .= cmd(img(geticon("delete")), "Exec('delete','zone_main', Hash('path', '".$child->getPath()."'))");
				}

				if ($child->hasRight("create_subnodes", array("forum_post")))
				{
					$admin .= cmd(img(geticon("comment"))."&nbsp;".ucfirst(i18n("answer")), "Exec('new','zone_main', Hash('path', '".$child->getPath()."', 'class_name', 'forum_post'))");
				}
				
				echo $admin;
				?>
				
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" id="left" width="100%">
			<hr size="1" color="#FCE464" width="99%">
			<div id="main">
				<?=$child->getVarValue("message")?>
			</div>
		</td>
	</tr>
	
</table>
<?
$com_data[$com_count]['children'] = fetch("FETCH node WHERE link:node_top='".$child->getNodeId()."' AND link:type='sub' NODESORTBY !property:version SORTBY property:created");

foreach ($com_data[$com_count]['children'] as $child)
{
	$com_count++;
	include(gettpl("show_line", $child));
	$com_count--;
}
?>
