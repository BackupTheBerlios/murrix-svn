<?

function DrawObjectForm($object)
{
	$vars = $object->GetVars();

	guiTitel("Create Object", "&nbsp;");
	?>
	<table class="simple" cellspacing="0" width="100%">
		<tr>
			<td class="simplemain">
		
				<form name="createForm" id="createForm" action="javascript:void(null);" onsubmit="sCreate_objectCall();">
					<div>Name:</div> <div><input type="text" name="name"></div><br/>
				
					<?
					if (isset($object->vars))
					{
						foreach ($object->vars as $var)
							echo "<div>".$var->GetName().": (".$var->type.")</div><div>".$var->GetEdit("createForm")."</div><br/>";
					}
					?>
					
					<div id="submitDiv" class="submitDiv"><input id="submitButton" type="submit" value="Create"/></div>
				</form>
			</td>
		</tr>
	</table>
	<?
}

function DrawClassForm()
{
	guiTitel("Create Object", "&nbsp;");
	?>
	<table class="simple" cellspacing="0" width="100%">
		<tr>
			<td class="simplemain">
				<form id="createForm" action="javascript:void(null);" onsubmit="sCreate_objectCall();">
					<select class="select" name="class" onchange="this.form.submit();">
					<?
						$list = mClass::GetNameList();
						for ($n = 0; $n < count($list); $n++)
							echo "<option value=\"".$list[$n]."\">".ucfirst($list[$n])."</option>";
					?>
					</select>
					<div id="submitDiv" class="submitDiv"><input id="submitButton" type="submit" value="Next"/></div>
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