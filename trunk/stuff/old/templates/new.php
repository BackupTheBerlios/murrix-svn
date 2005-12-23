<?
$object = new mObject();
$object->class_name = $_GET['class'];
$object->InitVars();
//$object->class = new mClass($object->class_name);

guiTitel("Create ".Img(guiIcon($object->GetIcon()))." Object of classtype ".$object->class_name);
?>
<table class="simple" cellspacing="0" width="100%">
	<tr>
		<td class="simplemain">
			
			<form action="./" method="post" enctype="multipart/form-data" name="form">
				
				<?/* Required vars */?>
				<input class="hidden" type="hidden" name="action" value="create">
				<input class="hidden" type="hidden" name="class" value="<?=$object->class_name?>">
				<input class="hidden" type="hidden" name="path" value="<?=$path?>">
				<input class="hidden" type="hidden" name="object" value="<?=$object->id?>">
				<b>Name:</b> <div><input type="text" name="name" value="<?=$object->name?>"></div><br>
				<?/* ============= */?>
				
				<?
				$vars = $object->GetVars();
				if (isset($vars))
				{
					foreach ($vars as $var)
						echo "<b>".$var->GetName().":</b> (".$var->type.")<div>".$var->GetEdit($object->id)."</div><br>";
				}
				?>
				<input type="submit" value="Save">
			</form>
		</td>
	</tr>
</table>

<script type="text/javascript">
	document.form.name.focus();
</script>