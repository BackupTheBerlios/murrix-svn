<?
$current_view = "edit";
include(gettpl("adminpanel", $object));

$vars = $object->GetVars();

$left = img(geticon("settings"))."&nbsp;".ucf(i18n("edit"));
$right = ucf(i18n("class")).": ".ucwords(str_replace("_", " ", $object->getClassName()));
$center = "";
include(gettpl("big_title"));

?>
<form name="sEdit" id="sEdit" action="javascript:void(null);" onsubmit="Post('edit', 'zone_main', 'sEdit');">
	<input class="hidden" type="hidden" name="action" value="save"/>
	<div class="main">
		<table class="top_edit_table">
			<tr>
				<td>
					<?=ucf(i18n("name"))?>: <input class="input" type="text" name="name" value="<?=$object->getName()?>">
				</td>
				<td>
					<?=ucf(i18n("icon"))?>: <input class="input" type="text" name="icon" value="<?=$object->getIcon(false)?>">
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
				echo $var->getName().": (".$var->getType().")<br/><div class=\"container\">".$var->getEdit("sEdit")."</div><br/>";
		}
		?>
		<input class="submit" id="submitButton" type="submit" value="<?=ucf(i18n("save"))?>"/>
	</div>
</form>

