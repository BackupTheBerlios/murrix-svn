<?

function DrawEditForm($object)
{
	$vars = $object->GetVars();
	?>
	<div class="titel">
		<?=Img(geticon($object->GetIcon()))?> Edit <?=ucfirst($object->class_name)?>
	</div>

	<div class="main">
		<table id="line" cellspacing="0">
			<tr>
				<td>
					<div class="main">
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
							<div id="submitDiv" class="submitDiv"><input id="submitButton" class="title" type="submit" value="Save"/></div>
						</form>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<?
}

function DrawNoRights()
{
	?>
	<div class="titel">
		No rights
	</div>

	<div class="main">
		<table id="line" cellspacing="0">
			<tr>
				<td>
					<div class="main">
						You do not have enough rights to create a object here.
					</div>
				</td>
			</tr>
		</table>
	</div>
	<?
}

?>