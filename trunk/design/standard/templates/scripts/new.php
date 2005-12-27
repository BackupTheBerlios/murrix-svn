<?
$class_form = "<form style=\"display: inline;\" name=\"sClassSelect\" id=\"sClassSelect\" action=\"javascript:void(null);\" onsubmit=\"javascript:void(null)\">";
$class_form .= "<select class=\"form\" onchange=\"Post('new','zone_main', 'sClassSelect');\" name=\"class_name\">";
$classlist = getClassList();
foreach ($classlist as $class_name)
{
	$selected = "";
	if ($class_name == $newobject->getClassName())
		$selected = "selected";

	$class_form .= "<option $selected value=\"$class_name\">".ucwords(str_replace("_", " ", $class_name))."</option>";
}
$class_form .= "</select>";
$class_form .= "</form>";

$current_view = "new";
include(gettpl("adminpanel", $newobject));

$vars = $newobject->GetVars();
$newobject->loadClassIcon();

$left = "<span style=\"font-weight: bold; font-size: 18px;\">".img(geticon($newobject->getIcon()))."&nbsp;".ucf(i18n("new"))."</span>";
$right = "<span style=\"font-weight: bold;\">".ucf(i18n("class")).":</span> $class_form";
$center = "";
include(gettpl("big_title"));

?>
							
<form name="sEdit" id="sEdit" action="javascript:void(null);" onsubmit="Post('new', 'zone_main', 'sEdit');">
	<input type="hidden" name="action" value="save">
	<input type="hidden" name="path" value="<?=$object->getPath()?>">
	<input type="hidden" name="class_name" value="<?=$newobject->getClassName()?>">
	<div class="main_bg" style="margin-top: 5px">
		<div class="main">
			<table id="invisible" cellspacing="0" width="100%">
				<tr>
					<td>
						<div id="main">
							<div><?=ucf(i18n("name"))?>:</div> <div><input class="form" type="text" name="name" value="<?=$newobject->getName()?>"></div>
						</div>
					</td>
					<td>
						<div id="main">
							<div><?=ucf(i18n("icon"))?>:</div> <div><input class="form" type="text" name="icon" value=""></div>
						</div>
					</td>
					<td>
						<div id="main">
							<div><?=ucf(i18n("language"))?>:</div>
							<div>
								<select class="form" name="language">
									<?
									$selected_lang = $newobject->getLanguage();
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
			if (isset($newobject->vars))
			{
				foreach ($newobject->vars as $var)
					echo "<div>".$var->getName().": (".$var->getType().")</div><div>".$var->getEdit("objectmanagerForm")."</div><br/>";
			}
			?>
			<input class="title" id="submitButton" type="submit" value="<?=ucf(i18n("save"))?>"/>
		</div>
	</div>
</form>

