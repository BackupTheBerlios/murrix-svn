<?
global $abspath;

$path = $args['path'];

$subitems = GetSubfilesAndSubfolders("$abspath/upload$path");

?>
<form name="fImport" id="fImport" action="javascript:void(null);" onsubmit="Post('import','fImport');">
	<div>
		<input class="hidden" type="hidden" name="action" value="import_upload"/>
		<input class="hidden" type="hidden" name="view" value="upload"/>
		<input class="hidden" type="hidden" name="path" value="<?=urlencode($path)?>"/>
		<input class="hidden" type="hidden" name="node_id" value="<?=$object->getNodeId()?>"/>
		<?
		echo compiletpl("title/medium", array("left"=>ucf(i18n("folder content"))));
		
		echo "$path";
		
		if (count($subitems) > 0)
		{
			$itemlist = array();
			$itemlist[] = array(ucf(i18n("name")));
			
			if (!empty($path) && $path != "/")
				$itemlist[] = array(cmd(img(geticon("back"))."&nbsp;".ucf(i18n("parent folder")), "exec=import&view=upload&node_id=".$object->getNodeId()."&path=".urlencode(GetParentPath($path)."/")));
			
			foreach ($subitems as $subitem)
			{
				$checkbox = "<input checked class=\"input\" type=\"checkbox\" name=\"filenames[]\" value=\"$subitem\"/>";
			
				if (is_dir("$abspath/upload/$path$subitem"))
					$itemlist[] = array("$checkbox&nbsp;".cmd(img(geticon("file_folder"))."&nbsp;".$subitem, "exec=import&view=upload&node_id=".$object->getNodeId()."&path=".urlencode("$path$subitem/")));
				else
				{
					$type = getfiletype(pathinfo("$abspath/upload/$path$subitem", PATHINFO_EXTENSION));
					$itemlist[] = array("$checkbox&nbsp;".img(geticon($type))."&nbsp;".$subitem);
				}
			}
			
			echo compiletpl("table", array("list"=>$itemlist, "endstring"=>"% ".i18n("rows")));
			?>
			<input class="submit" type="button" onclick="checkUncheckAll(this)" value="<?=ucf(i18n("invert selection"))?>"/>
			<input class="submit" id="submitButton" type="submit" value="<?=ucf(i18n("import"))?>"/>
		<?
		}
		else
		{
		?>
			<div class="main">
				<div class="container">
					<?=ucf(i18n("upload folder is empty"))?>
				</div>
			</div>
		<?
		}
		?>
	</div>
</form>