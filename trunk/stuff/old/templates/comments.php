<?

function DrawComments($object)
{
	$path = $_SESSION['murrix']['path'];
	if (HasRight("create", $path, array("comment")))
	{
		echo "<a class=\"\" href=\"?path=$path&action=new&class=comment\">";
		echo Img(guiIcon("comment"));
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
		<div id="contact_person">
			<div id="frame">
				
				<div id="title">
					Comments
				</div>
				<?
	
				
				foreach ($comments as $comment)
					PrintComment($hasmap, $comment);
				?>
			</div>
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
					echo "<a class=\"title\" href=\"?path=".$comment->GetPath()."\">".Img(guiIcon($comment->GetIcon()))."</a>";
					echo "&nbsp;";
					echo "<a class=\"title\" href=\"?path=".$comment->GetPath()."\">".$comment->name."</a><br/>";
					
					if ($comment->creator == 0)
						echo "Unknown";
					else
					{
						$creator = new mObject($comment->creator);
						$creator_path = $creator->GetPath();
							
						if (empty($creator_path))
							echo $creator->name;
						else
							echo "<a href=\"?path=$creator_path\">".$creator->name."</a>";
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
						$admin .= "<a class=\"\" href=\"?path=".$comment->GetPath()."&action=edit\">";
						$admin .= Img(guiIcon("edit"));
						//$admin .= "&nbsp;";
						//$admin .= "Edit";
						$admin .= "</a>";
						$admin .= "&nbsp;";
					}
					
					if (HasRight("delete", $comment->GetPath()))
					{
						//$admin .= "&#183;";
						$admin .= "&nbsp;";
						$admin .= "<a class=\"\" href=\"?path=".$comment->GetPath()."&action=delete\" onclick=\"return confirmAction('Are you sure you want to delete $path')\">";
						$admin .= Img(guiIcon("delete"));
						//$admin .= "&nbsp;";
						//$admin .= "Delete";
						$admin .= "</a>";
						$admin .= "&nbsp;";
					}
					
					if (HasRight("create", $comment->GetPath(), array("comment")))
					{
						$admin .= "<a class=\"\" href=\"?path=".$comment->GetPath()."&action=new&class=comment\">";
						$admin .= Img(guiIcon("comment"));
						$admin .= "&nbsp;";
						$admin .= "Answer";
						$admin .= "</a>";
						$admin .= "&nbsp;";
					}
					
					echo $admin;
					/*
					echo "EDIT: ".HasRight("edit", $comment->GetPath())."<br>";
					echo "DELETE: ".HasRight("delete", $comment->GetPath())."<br>";
					echo "READ: ".HasRight("read", $comment->GetPath())."<br>";
					echo "CREATE: ".HasRight("create", $comment->GetPath())."<br>";
					echo "CREATE COMMENT: ".HasRight("create", $comment->GetPath(), array("comment"))."<br>";
					*/
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