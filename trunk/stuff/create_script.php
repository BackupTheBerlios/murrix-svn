<?

function DrawObjectForm($object)
{
	$vars = $object->GetVars();
	?>
	<div class="titel">
		<?=Img(geticon($object->GetIcon()))?> Create <?=ucfirst($object->class_name)?>
	</div>

	<div class="main">
		<table id="line" cellspacing="0">
			<tr>
				<td>
					<div class="main">
						<form name="createForm" id="createForm" action="javascript:void(null);" onsubmit="sCreate_objectCall();">
							<div>Name:</div> <div><input type="text" name="name"></div><br/>
						
							<?
							if (isset($object->vars))
							{
								foreach ($object->vars as $var)
									echo "<div>".$var->GetName().": (".$var->type.")</div><div>".$var->GetEdit("createForm")."</div><br/>";
							}
							?>
							
							<div id="submitDiv" class="submitDiv"><input id="submitButton" class="title" type="submit" value="Create"/></div>
						</form>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<?
}

function DrawClassForm()
{
	?>
	<div class="titel">
		Create Object
	</div>

	<div class="main">
		<table id="line" cellspacing="0">
			<tr>
				<td>
					<div class="main">
						<form id="createForm" action="javascript:void(null);" onsubmit="sCreate_objectCall();">
							<select class="select" name="class" onchange="this.form.submit();">
							<?
								$list = mClass::GetNameList();
								for ($n = 0; $n < count($list); $n++)
									echo "<option value=\"".$list[$n]."\">".ucfirst($list[$n])."</option>";
							?>
							</select>
							<br/>
							<br/>
							<div id="submitDiv" class="submitDiv"><input id="submitButton" class="title" type="submit" value="Next"/></div>
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