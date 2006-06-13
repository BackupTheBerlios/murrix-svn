<?
$current_view = "edit";
include(gettpl("adminpanel", $object));

$vars = $object->GetVars();

$left = img(geticon("edit"))."&nbsp;".ucf(i18n("edit"));
$right = ucf(i18n("class")).": ".ucw(str_replace("_", " ", $object->getClassName()));
$center = "";
include(gettpl("big_title"));

?>
<form name="sEdit" id="sEdit" action="javascript:void(null);" onsubmit="Post('edit', 'zone_main', 'sEdit');">
	<input class="hidden" type="hidden" name="action" value="save"/>
	<input class="hidden" type="hidden" name="node_id" value="<?=$object->getNodeId()?>"/>
	<div class="main">
		<table class="top_edit_table">
			<tr>
				<td>
					<?=ucf(i18n("name"))?>: <input class="input" type="text" name="name" value="<?=$object->getName()?>">
				</td>
				<td>
					<?=ucf(i18n("icon"))?>:
					<img id="icon_img" src="<?=geticon($object->getIcon())?>"/>
					<input class="hidden" type="hidden" name="icon" id="icon" value="<?=$object->getIcon(false)?>"/>
					<a href="javascript:void(null);" onclick="popWin=open('icon_browse.php?input_id=icon&form_id=sEdit','PopUpWindow','width=500,height=400,scrollbars=1,status=0'); popWin.opener = self; popWin.focus(); popWin.moveTo(150,50); return false"><?=ucf(i18n("browse"))?></a>
				</td>
				<td>
					<?=ucf(i18n("language"))?>:
					<select class="select" name="language">
						<?
						$selected_lang = $object->getLanguage();
						if (empty($selected_lang))
							$selected_lang = $_SESSION['murrix']['language'];

						foreach ($_SESSION['murrix']['languages'] as $language)
						{
							?><option value="<?=$language?>" <?=($language == $selected_lang ? "selected" : "")?>><?=ucf(i18n($language))?></option><?
						}
						?>
					</select>
				</td>
			</tr>
		</table>
	</div>
	<div class="main">
		<?
		if (isset($object->vars))
		{
			foreach ($object->vars as $var)
			{
				$req = "";
				if ($var->getRequired())
					$req = "<span style=\"color: red;\">*</span> ";
				?>
				<div class="container">
					<fieldset>
						<legend>
							<?=$req.ucf(str_replace("_", " ", i18n($var->getName(true))))?> (<?=$var->getType()?>)
						</legend>
						<?
						echo $var->getComment();
						echo $var->getEdit("sEdit");
						
						$javascript .= $var->getJavascript("sEdit");
						?>
					</fieldset>
				</div>
				<br/>
			<?
			}
		}
		?>
		<input class="submit" id="submitButton" type="submit" value="<?=ucf(i18n("save"))?>"/> <input checked type="checkbox" name="newversion"/> <?=ucf(i18n("save as new version"))?>
	</div>
</form>

