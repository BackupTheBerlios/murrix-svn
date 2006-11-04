<?
$newobject = new mObject();
$newobject->setClassName($args['class_name']);
$newobject->loadVars();

$vars = $newobject->getVars();

$varlist = array();
$varlist[] = "ignore";
$varlist[] = "name";

foreach ($vars as $var)
	$varlist[] = $var->name;
	
$line_parts = explode($args['delimiter'], $args['line'])
?>
<form name="fImport" id="fImport" action="javascript:void(null);" onsubmit="Post('import','fImport');">
	<div class="main">
		<div class="container">
			<input class="hidden" type="hidden" name="action" value="import_custom2"/>
			<input class="hidden" type="hidden" name="view" value="import"/>
			<input class="hidden" type="hidden" name="class_name" value="<?=$args['class_name']?>"/>
			<input class="hidden" type="hidden" name="file" value="<?=$args['file']?>"/>
			<input class="hidden" type="hidden" name="node_id" value="<?=$object->getNodeId()?>"/>
			
			<div class="container">
				<fieldset>
					<legend>
						<?=ucf(i18n("field data"))?>
					</legend>
					
					<table>
						<tr>
							<td>
								<?=ucf(i18n("data from file"))?>
							</td>
							<td>
								<?=ucf(i18n("class variable"))?>
							</td>
						</tr>
						<?
						for ($n = 0; $n < $args['number']; $n++)
						{
						?>
							<tr>
								<td>
								<?
									if (isset($line_parts[$n]))
										echo $line_parts[$n];
									else
										echo "<i>".ucf(i18n("empty"))."</i>";
								?>
								</td>
								<td>	
									<select class="select" name="fields[]">
									<?
										foreach ($varlist as $var)
											echo "<option value=\"$var\">".ucf(str_replace("_", " ", $var))."</option>";
									?>
									</select>
								</td>
							</tr>
						<?
						}
					?>
					</table>
				</fieldset>
			</div>
			
			<input class="submit" id="submitButton" type="submit" value="<?=ucf(i18n("import"))?>"/>
		</div>
	</div>
</form>