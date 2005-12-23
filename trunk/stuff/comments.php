<?

function DrawComments($object)
{
	$path = $_SESSION['murrix']['path'];
	if (HasRight("create", $path, array("comment")))
	{
		echo "<a class=\"\" onclick=\"SystemRunScript('create_object','zone_main', Hash('path', '$path', 'class', 'comment'));\" href=\"javascript:void(null);\">";
		echo Img(geticon("comment"));
		echo "&nbsp;";
		echo "Add Comment";
		echo "</a>";
	}
	
	$hasmap = array(array("classes", array("comment")),
			array("type", array("sub")),
			array("side", "bottom"),
			array("sort", array("created"), true));
						
						
	$comments = $object->GetRelatedHash($hasmap);
	if (count($comments) > 0)
	{
		?>
		<div id="frame">
			
			<div id="title">
				Comments
			</div>
			<?
			foreach ($comments as $comment)
				PrintComment($hasmap, $comment);
			?>
		</div>
	<?
	}
}

function PrintComment($hasmap, $comment, $indent = 0)
{
	$author = new mObject($comment->GetValue("author"));
	?>
	<table id="event" style="margin-left: <?=($indent*30)?>px;" cellspacing="0">
		<tr>
			<td id="left">
				<div id="main">
					<?
					echo "<a class=\"title\" onclick=\"SystemRunScript('show','zone_main', Hash('path', '".$comment->GetPath()."'));\" href=\"javascript:void(null);\">".Img(geticon($comment->GetIcon()))."</a>";
					echo "&nbsp;";
					echo "<a class=\"title\" onclick=\"SystemRunScript('show','zone_main', Hash('path', '".$comment->GetPath()."'));\" href=\"javascript:void(null);\">".$comment->name."</a><br/>";
					
					if ($comment->creator == 0)
						echo "Unknown";
					else
					{
						$creator = new mObject($comment->creator);
						$creator_path = $creator->GetPath();
							
						if (empty($creator_path))
							echo $creator->name;
						else
							echo "<a onclick=\"SystemRunScript('show','zone_main', Hash('path', '$creator_path'));\" href=\"javascript:void(null);\">".$creator->name."</a>";
					}
					
					?>
				</div>
			</td>
			<td id="right">
				<div id="main">
				<?=$comment->created?><br/>
					<?
					
					$admin = "";

					if (HasRight("edit", $comment->GetPath()))
					{
						//$admin .= "&#183;";
						$admin .= "&nbsp;";
						$admin .= "<a onclick=\"SystemRunScript('edit_object','zone_main', Hash('path', '".$comment->GetPath()."'));\" href=\"javascript:void(null);\">";
						$admin .= Img(geticon("edit"));
						//$admin .= "&nbsp;";
						//$admin .= "Edit";
						$admin .= "</a>";
						$admin .= "&nbsp;";
					}
					
					if (HasRight("delete", $comment->GetPath()))
					{
						//$admin .= "&#183;";
						$admin .= "&nbsp;";
						$admin .= "<a onclick=\"SystemRunScript('delete_object','zone_main', Hash('path', '".$comment->GetPath()."'));\" href=\"javascript:void(null);\">";
						$admin .= Img(geticon("delete"));
						//$admin .= "&nbsp;";
						//$admin .= "Delete";
						$admin .= "</a>";
						$admin .= "&nbsp;";
					}
					
					if (HasRight("create", $comment->GetPath(), array("comment")))
					{
						$admin .= "<a onclick=\"SystemRunScript('create_object','zone_main', Hash('path', '".$comment->GetPath()."', 'class', 'comment'));\" href=\"javascript:void(null);\">";
						$admin .= Img(geticon("comment"));
						$admin .= "&nbsp;";
						$admin .= "Answer";
						$admin .= "</a>";
						$admin .= "&nbsp;";
					}
					
					echo $admin;
					?>
					
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" id="left">
				<hr size="1" color="#ADADFF" width="99%">
				<div id="main">
					<?=$comment->GetValue("message")?>
				</div>
			</td>
		</tr>
		
	</table>
	<?
	
	$comments = $comment->GetRelatedHash($hasmap);

	if (count($comments) > 0)
	{
		for ($n = 0; $n < count($comments); $n++)
			PrintComment($hasmap, $comments[$n], $indent+1);
	}
}
?>