<?
global $abspath;
?>
<form name="fImport" id="fImport" action="javascript:void(null);" onsubmit="Post('import','fImport');">
	<div class="main">
		<div class="container">
			<input class="hidden" type="hidden" name="action" value="import_xml"/>
			<input class="hidden" type="hidden" name="view" value="xml"/>
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
						<?=ucf(i18n("files"))?>
					</legend>
					<?=ucf(i18n("folder with backuped files"))?><br/>
					<input class="input_big" id="filepath" name="filepath" type="text" value="<?=$abspath?>/"/><br/>
					
					<?=ucf(i18n("folder with backuped thumbnailes"))?><br/>
					<input class="input_big" id="thumbpath" name="thumbpath" type="text" value="<?=$abspath?>/"/><br/>
				</fieldset>
			</div>
			
			<input class="submit" id="submitButton" type="submit" value="<?=ucf(i18n("import"))?>"/>
		</div>
	</div>
</form>