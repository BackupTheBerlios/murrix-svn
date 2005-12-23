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
				?>
			</div>
		</td>
		<td id="right">
			<div id="main">
				<?=$child->getCreated()?>
				<br/>
				<?
				
				$admin = "";

				if ($child->hasRight("edit"))
				{
					$admin .= cmd(img(geticon("edit")), "Exec('objectmanager','zone_main', Hash('action', 'editversion', 'object_id', '".$child->getId()."'))");
				}
				
				if ($child->hasRight("delete"))
				{
					$admin .= "&nbsp;";
					$admin .= "<a onclick=\"Exec('objectmanager','zone_main', Hash('action', 'deletenode', 'node_id', '".$child->getNodeId()."'));\" href=\"javascript:void(null);\">";
					$admin .= img(geticon("delete"));
					$admin .= "</a>";
				}

				if ($child->hasRight("create_subnodes", array("comment")))
				{
					$admin .= cmd(img(geticon("comment"))."&nbsp;".ucfirst(i18n("answer")), "Exec('objectmanager','zone_main', Hash('action', 'newsubnode', 'node_id', '".$child->getNodeId()."', 'class_name', 'comment'))");
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
