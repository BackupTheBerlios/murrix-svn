<form name="fImport" id="fImport" action="javascript:void(null);" onsubmit="Post('import','fImport');">
	<div class="main">
		<div class="container">
			<input class="hidden" type="hidden" name="action" value="import_custom"/>
			<input class="hidden" type="hidden" name="view" value="import"/>
			<input class="hidden" type="hidden" name="node_id" value="<?=$object->getNodeId()?>"/>
			
			<div class="container">
				<fieldset>
					<legend>
						<?=ucf(i18n("file"))?>
					</legend>
					<iframe style="border: none; width: 350px; height: 25px; text-align: right;" src="<?=gettpl_www("popups/fileupload")?>?varid=file&parent_id=<?=$object->getNodeId()?>"></iframe><br/>
					<input disabled class="input_big" id="nfile" name="nfile" type="text" value="">
					<input class="hidden" id="file" name="file" type="hidden" value=""/><br/>
				</fieldset>
			</div>
			
			<div class="container">
				<fieldset>
					<legend>
						<?=ucf(i18n("settings"))?>
					</legend>
					<?=ucf(i18n("delimiter"))?><br/>
					<input class="input" id="delimiter" name="delimiter" type="text" value=";"/><br/>
					<?=ucf(i18n("class"))?><br/>
					<select class="select" name="class_name">
					<?
						$classlist = getClassList();
						foreach ($classlist as $class_name)
						{
							$selected = $class_name == $args['class_name'] ? "selected" : "";
							echo "<option $selected value=\"$class_name\">".ucf(str_replace("_", " ", $class_name))."</option>";
						}
					?>
					</select>
				</fieldset>
			</div>
			
			<input class="submit" id="submitButton" type="submit" value="<?=ucf(i18n("begin import"))?>"/>
		</div>
	</div>
</form>