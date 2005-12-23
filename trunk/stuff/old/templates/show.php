<?

function DrawTemplate($object)
{
	global $abspath, $wwwpath;
	
	$admin = "<a href=\"#\" onClick=\"return clickreturnvalue()\" onMouseover=\"dropdownmenu(this, event, menuItems, '150px')\" onMouseout=\"delayhidemenu()\">";
	$admin .= Img(guiIcon("settings"));
	//$admin .= "&nbsp;Administration";
	$admin .= "</a>";
	
	/*
	
	if (HasRight("edit", $path))
	{
		$admin .= "&#183;";
		$admin .= "&nbsp;";
		$admin .= "<a class=\"\" href=\"?path=$path&action=links\">";
		$admin .= Img(guiIcon("link"));
		$admin .= "&nbsp;";
		$admin .= "Manage Links";
		$admin .= "</a>";
	}*/
	?>

	<? guiTitel2(array(Img(guiIcon($object->GetIcon()))." ".$object->name, ""), array("", $admin)) ?>
		
	<table class="simple" cellspacing="0" width="100%">
		<tr>
			<td class="simplemain">
			<?
				echo "<b>Creator:</b> <div>";
				if ($object->creator == 0)
					echo "Unknown";
				else
				{
					$creator = new mObject($object->creator);
					$creator_path = $creator->GetPath();
						
					if (empty($creator_path))
						echo $creator->name;
					else
						echo "<a href=\"?path=$creator_path\">".$creator->name."</a>";
				}
				echo "</div><br/>";
				
				echo "<b>Created:</b> <div>".$object->created."</div><br/>";
				
				echo "<b>Modified:</b> <div>".$object->modified."</div><br/>";
				
				$vars = $object->GetVars();
				if (!empty($vars))
				{
					foreach ($vars as $var)
					{
						$value = $var->GetValue();
						if (!empty($value))
						{
							if (is_array($value))
							{
								echo "<b>".$var->GetName().":</b> <div>";
								foreach ($value as $line)
									echo $line."<br/>";
								echo "</div><br/>";
							}
							else
								echo "<b>".$var->GetName().":</b> <div>$value</div><br/>";
						}
					}
				}
			?>
			</td>
		</tr>
	</table>
	
	<?
	$childlist[] = array("Name", "Path");
	
	$children = mObject::GetRelatedWithRights($object->GetRelatedHash(array(
										array("classes", array("comment"), true),
										array("type", array("sub")),
										array("side", "bottom"))));
	
	foreach ($children as $child)
		$childlist[] = array("<a href=\"".guiPath($child->GetPath())."\"><img border=\"0\" align=\"middle\" src=\"".guiIcon($child->GetIcon())."\"> $child->name</a>", $child->GetPath());
		
	$parentlist[] = array("Name", "Path");
	$parents = mObject::GetRelatedWithRights($object->GetRelatedHash(array(
										array("type", array("sub")),
										array("side", "top"))));
	foreach ($parents as $parent)
		$parentlist[] = array("<a href=\"".guiPath($parent->GetPath())."\"><img border=\"0\" align=\"middle\" src=\"".guiIcon($parent->GetIcon())."\"> $parent->name</a>", $parent->GetPath());
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
	
	require_once("$abspath/design/templates/comments.php");
	DrawComments($object);
}
?>