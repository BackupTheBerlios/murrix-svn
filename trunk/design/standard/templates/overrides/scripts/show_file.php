<?
function DrawObject($object)
{
	global $abspath, $wwwpath;
		
	$admin = "<a href=\"#\" onClick=\"return clickreturnvalue()\" onMouseover=\"dropdownmenu(this, event, menuItems, '150px')\" onMouseout=\"delayhidemenu()\">";
	$admin .= Img(geticon("settings"));
	//$admin .= "&nbsp;Administration";
	$admin .= "</a>";
	?>
	
	<? guiTitel2(array(Img(geticon($object->GetIcon()))." ".$object->name, ""), array($object->GetValue("description"), $admin)) ?>
		
	<table class="simple" cellspacing="0" width="100%">
		<tr>
			<td class="simplemain">
			<?
				$filename = $object->GetValue("file");
				echo "<div>";
				ShowFile($filename);
				echo "</div>";
			?>
			</td>
		</tr>
	</table>
	<table class="simple" cellspacing="0" width="100%">
		<tr>
			<td class="simplemain">
				<table class="invisible" cellspacing="0" width="100%">
					<tr>
						<td class="text">
							Filename
						</td>
						<td class="text">
							Creator
						</td>
						<td class="text">
							Created
						</td>
						<td class="text">
							Modified
						</td>
					</tr>
					<tr>
						<td class="simplemain">
							<?=$object->GetValue("file", true)?>
						</td>
						<td class="simplemain">
						<?
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
						?>
						</td>
						<td class="simplemain">
							<?=$object->created?>
						</td>
						<td class="simplemain">
							<?=$object->modified?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<?
	
	require_once(gettpl("comments.php"));
	DrawComments($object);
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