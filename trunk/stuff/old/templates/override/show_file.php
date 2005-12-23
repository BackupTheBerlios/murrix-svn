	<?
	function DrawTemplate($object)
	{
		global $abspath, $wwwpath;
		
	$admin = "";
	
	if (HasRight("create", $path))
	{
		$admin .= Img(guiIcon("folder"));
		$admin .= "&nbsp;";
		$admin .= "<form action=\"\" method=\"get\">";
		$admin .= "<input class=\"hidden\" type=\"hidden\" name=\"path\" value=\"$path\">";
		$admin .= "<input class=\"hidden\" type=\"hidden\" name=\"action\" value=\"new\">";
		$admin .= "<select class=\"select\" name=\"class\" onchange=\"this.form.submit();\">";
		$admin .= "<option value=\"\" selected=\"selected\">New Subobject</option>";
		
		$list = mClass::GetNameList();
		for($n = 0; $n < count($list); $n++)
			$admin .= "<option value=\"".$list[$n]."\">".ucfirst($list[$n])."</option>";
				
		$admin .= "</select>";
		$admin .= "</form>";
		$admin .= "&nbsp;";
	}
	
	if (HasRight("edit", $path))
	{
		$admin .= "&#183;";
		$admin .= "&nbsp;";
		$admin .= "<a class=\"\" href=\"?path=$path&action=edit\">";
		$admin .= Img(guiIcon("edit"));
		$admin .= "&nbsp;";
		$admin .= "Edit";
		$admin .= "</a>";
		$admin .= "&nbsp;";
	}
	
	if (HasRight("delete", $path))
	{
		$admin .= "&#183;";
		$admin .= "&nbsp;";
		$admin .= "<a class=\"\" href=\"?path=$path&action=delete\" onclick=\"return confirmAction('Are you sure you want to delete $path')\">";
		$admin .= Img(guiIcon("delete"));
		$admin .= "&nbsp;";
		$admin .= "Delete";
		$admin .= "</a>";
		$admin .= "&nbsp;";
	}
	
	if (HasRight("edit", $path))
	{
		$admin .= "&#183;";
		$admin .= "&nbsp;";
		$admin .= "<a class=\"\" href=\"?path=$path&action=links\">";
		$admin .= Img(guiIcon("link"));
		$admin .= "&nbsp;";
		$admin .= "Manage Links";
		$admin .= "</a>";
	}
	?>
	
	<? guiTitel2(array(Img(guiIcon($object->GetIcon()))." ".$object->name, ""), array($object->GetValue("description"), $admin)) ?>
		
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
	
	require_once("$abspath/design/templates/comments.php");
	DrawComments($object);
}
?>