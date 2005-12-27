<?

$current_view = "edit";
include(gettpl("adminpanel", $object));

$vars = $object->GetVars();

$left = "<span style=\"font-weight: bold; font-size: 18px;\">".img(geticon("settings"))."&nbsp;".ucf(i18n("edit"))."</span>";
$right = "<span style=\"font-weight: bold;\">".ucf(i18n("class")).":</span> ".ucwords(str_replace("_", " ", $object->getClassName()));
$center = "";
include(gettpl("big_title"));

?>
<form name="sEdit" id="sEdit" action="javascript:void(null);" onsubmit="Post('edit', 'zone_main', 'sEdit');">
	<input type="hidden" name="action" value="save">
	<div class="main_bg" style="margin-top: 5px">
		<div class="main">
			<table id="invisible" cellspacing="0" width="100%">
				<tr>
					<td>
						<div id="main">
							<div><?=ucf(i18n("name"))?>:</div> <div><input class="form" type="text" name="name" value="<?=$object->getName()?>"></div>
						</div>
					</td>
					<td>
						<div id="main">
							<div><?=ucf(i18n("icon"))?>:</div> <div><input class="form" type="text" name="icon" value="<?=$object->getIcon(false)?>"></div>
						</div>
					</td>
					<td>
						<div id="main">
							<div><?=ucf(i18n("language"))?>:</div>
							<div>
								<select class="form" name="language">
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
							</div>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="main_bg" style="margin-top: 5px">
		<div class="main">
			<?
			if (isset($object->vars))
			{
				foreach ($object->vars as $var)
					echo "<div>".$var->getName().": (".$var->getType().")</div><div>".$var->getEdit("objectmanagerForm")."</div><br/>";
			}
			?>
			<input class="title" id="submitButton" type="submit" value="<?=ucf(i18n("save"))?>"/>
		</div>
	</div>
</form>

