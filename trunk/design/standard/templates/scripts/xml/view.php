<?
global $abspath;
echo compiletpl("title/big", array("left"=>img(geticon("list"))."&nbsp;XML ".ucf(i18n("import"))."/".ucf(i18n("export"))));
?>
<div class="xml_wrapper">
	<div class="main">
		<fieldset>
			<legend>
				<?=ucf(i18n("export"))?>
			</legend>
			
			<form name="sExport" id="sExport" action="index.php?xml">
				<div>
					<input class="hidden" type="hidden" name="xml" value=""/>
					
					<?=ucf(i18n("name"))?><br/>
					<input class="input" type="input" name="name" value="MURRiX Export"/><br/>
					<?=ucf(i18n("description"))?><br/>
					<textarea class="input"></textarea><br/>
					<br/>
					<?=ucf(i18n("options"))?><br/>
					<input disabled class="input" type="checkbox" name="user" value="on"/> User/groups<br/>
					<input class="input" type="checkbox" name="links" value="on" checked/> Links<br/>
					<input class="input" type="checkbox" name="metadata" value="on" checked/> Metadata<br/>
					<input class="input" type="checkbox" name="allversions" value="on" checked/> All versions<br/>
					<input class="input" type="checkbox" name="allnodes" value="on" checked/> All nodes<br/>
					<br/>
					<input class="submit" id="submitButton" type="submit" value="<?=ucf(i18n("export"))?>"/>
				</div>
			</form>
		</fieldset>
		<fieldset>
			<legend>
				<?=ucf(i18n("import"))?>
			</legend>
			
			<form name="sImport" id="sImport" action="javascript:void(null);" onsubmit="Post('xml','sImport');">
				<div>
					<input class="hidden" type="hidden" name="action" value="import"/>
					<?=ucf(i18n("file"))?><br/>
					<iframe style="border: none; width: 350px; height: 25px; text-align: right;" src="<?=gettpl_www("popups/fileupload")?>?varid=file"></iframe><br/>
					<input disabled class="input_big" id="nfile" name="nfile" type="text" value="">
					<input class="hidden" id="file" name="file" type="hidden" value=""/><br/>
					
					<?=ucf(i18n("import node"))?><br/>
					<input class="input" id="node_id" name="node_id" type="text" value=""/>
					<a href="javascript:void(null);" onclick="popWin=open('<?=gettpl_www("popups/nodebrowse")?>?input_id=node_id','PopUpWindow','width=300,height=300,scrollbars=1,status=0'); popWin.opener = self; popWin.focus(); popWin.moveTo(150,50); return false"><?=ucf(i18n("browse"))?></a><br/>
					
					<?=ucf(i18n("folder with backuped files"))?><br/>
					<input class="input_big" id="filepath" name="filepath" type="text" value="<?=$abspath?>/"/><br/>
					
					<?=ucf(i18n("folder with backuped thumbnailes"))?><br/>
					<input class="input_big" id="thumbpath" name="thumbpath" type="text" value="<?=$abspath?>/"/><br/>
					
					<br/>
					<input class="submit" id="submitButton" type="submit" value="<?=ucf(i18n("import"))?>"/><br/>
					<?=$_SESSION['murrix']['system']->createZone("zone_import_log")?>
				</div>
			</form>
		</fieldset>
	</div>
</div>