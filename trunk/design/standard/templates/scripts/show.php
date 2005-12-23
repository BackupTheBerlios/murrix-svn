<?

function DrawObject($object)
{
	global $abspath, $wwwpath;
/*	
	echo "<a class=\"\" onclick=\"SystemRunScript('create_object','zone_main', Hash('path', '".$object->GetPath()."', 'class', 'file'));\" href=\"javascript:void(null);\">";
	echo Img(geticon("file"));
	echo "&nbsp;";
	echo "Add File";
	echo "</a><br>";
*/

$admin = "<a onclick=\"SystemRunScript('objectmanager','zone_main', Hash('node_id', '".$object->getNodeId()."'));\" href=\"javascript:void(null);\">";
	$admin .= Img(geticon("settings"));
	$admin .= "&nbsp;Object Manager";
	$admin .= "</a>";
	/*
	$admin = "<a href=\"#\" onClick=\"return clickreturnvalue()\" onMouseover=\"dropdownmenu(this, event, menuItems, '150px')\" onMouseout=\"delayhidemenu()\">";
	$admin .= Img(geticon("settings"));
	$admin .= "&nbsp;Administration";
	$admin .= "</a>";
	*/
	/*
	
	if (HasRight("edit", $path))
	{
		$admin .= "&#183;";
		$admin .= "&nbsp;";
		$admin .= "<a class=\"\" href=\"?path=$path&action=links\">";
		$admin .= Img(geticon("link"));
		$admin .= "&nbsp;";
		$admin .= "Manage Links";
		$admin .= "</a>";
	}*/
	?>

	<? guiTitel2(array(Img(geticon($object->getIcon()))." ".$object->getName(), ""), array("", $admin)) ?>
		
	<table class="simple" cellspacing="0" width="100%">
		<tr>
			<td class="simplemain">
			<?
				echo "<b>Creator:</b> <div>";
				if ($object->getCreator() == 0)
					echo "Unknown";
				else
				{
					$creator = new mObject($object->getCreator());
					$creator_path = $creator->getPath();
						
					if (empty($creator_path))
						echo $creator->getName();
					else
						echo "<a onclick=\"SystemRunScript('show','zone_main', Hash('path', '$creator_path'));\" href=\"javascript:void(null);\">".$creator->getName()."</a>";
				}
				echo "</div><br/>";
				
				echo "<b>Created:</b> <div>".$object->getCreated()."</div><br/>";
				
				echo "<b>Version:</b> <div>".$object->getVersion()."</div><br/>";

				echo "<b>Language:</b> <div>".$object->getLanguage()."</div><br/>";
				
				$vars = $object->getVars();
				if (!empty($vars))
				{
					foreach ($vars as $var)
					{
						$value = $var->getValue();
						if (!empty($value))
						{
							if (is_array($value))
							{
								echo "<b>".$var->getName().":</b> <div>";
								foreach ($value as $line)
									echo $line."<br/>";
								echo "</div><br/>";
							}
							else
								echo "<b>".$var->getName().":</b> <div>$value</div><br/>";
						}
					}
				}
			?>
			</td>
		</tr>
	</table>
	
	<?
	$childlist[] = array("Name", "Path");
/*	
	$children = mObject::GetRelatedWithRights($object->GetRelatedHash(array(
										array("classes", array("comment"), true),
										array("type", array("sub")),
										array("side", "bottom"))));
*/
	$children = fetch("FETCH object WHERE property:language='".$object->getLanguage()."' AND link:node_top='".$object->getNodeId()."' AND link:type='sub' AND !property:class_name='comment' GROUPBY property:node_id");
	
	foreach ($children as $child)
		$childlist[] = array("<a onclick=\"SystemRunScript('show','zone_main', Hash('path', '".$child->getPath()."'));\" href=\"javascript:void(null);\"><img border=\"0\" align=\"middle\" src=\"".geticon($child->getIcon())."\"> $child->name</a>", $child->getPath());
		
	$parentlist[] = array("Name", "Path");
/*	$parents = mObject::GetRelatedWithRights($object->GetRelatedHash(array(
										array("type", array("sub")),
										array("side", "top"))));
*/
	$parents = fetch("FETCH object WHERE property:language='".$object->getLanguage()."' AND link:node_bottom='".$object->getNodeId()."' AND link:type='sub' GROUPBY property:node_id");

	foreach ($parents as $parent)
		$parentlist[] = array("<a onclick=\"SystemRunScript('show','zone_main', Hash('path', '".$parent->getPath()."'));\" href=\"javascript:void(null);\"><img border=\"0\" align=\"middle\" src=\"".geticon($parent->getIcon())."\"> $parent->name</a>", $parent->getPath());
	?>
	
	<table class="invisible" cellspacing="0" width="100%">
		<tr>
			<td class="invisible" width="50%">
				<div class="titel">
					<div class="text">
						Child Objects
					</div>
					<div>
						<? guiList($childlist) ?>
					</div>
				</div>
			</td>
			<td class="invisible" width="50%">
				<div class="titel">
					<div class="text">
						Parent Objects
					</div>
					<div>
						<? guiList($parentlist) ?>
					</div>
				</div>
			</td>
		</tr>
	</table>
	
	<?
	/*
	require_once(gettpl("comments.php"));
	DrawComments($object);*/
}

function DrawNoRights()
{
	guiTitel("No rights", "&nbsp;");
	?>
	<table class="simple" cellspacing="0" width="100%">
		<tr>
			<td class="simplemain">
				You do not have enough rights to view this object.
			</td>
		</tr>
	</table>
	<?
}
?>