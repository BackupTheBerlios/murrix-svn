<?

function DrawEditForm($object)
{
	$vars = $object->GetVars();

	guiTitel("Edit Object", "&nbsp;");
	?>
	<table class="simple" cellspacing="0" width="100%">
		<tr>
			<td class="simplemain">
				<form name="editForm" id="editForm" action="javascript:void(null);" onsubmit="sEdit_objectCall();">
					<div>Name:</div> <div><input type="text" name="name" value="<?=$object->name?>"></div>
					<br/>
					<?
					if (isset($object->vars))
					{
						foreach ($object->vars as $var)
							echo "<div>".$var->GetName().": (".$var->type.")</div><div>".$var->GetEdit("editForm")."</div><br/>";
					}
					?>
					<div id="submitDiv" class="submitDiv"><input id="submitButton" type="submit" value="Save"/></div>
				</form>
			</td>
		</tr>
	</table>
	<?
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