<?
$class_form = "<form name=\"sClassSelect\" id=\"sClassSelect\" action=\"javascript:void(null);\" onsubmit=\"javascript:void(null)\">";
$class_form .= "<select class=\"select\" onchange=\"Post('new','zone_main', 'sClassSelect');\" name=\"class_name\">";
$classlist = getClassList();
foreach ($classlist as $class_name)
{
	$selected = "";
	if ($class_name == $newobject->getClassName())
		$selected = "selected";

	if ($object->hasRight("create_subnodes", array($class_name)))
		$class_form .= "<option $selected value=\"$class_name\">".ucwords(str_replace("_", " ", $class_name))."</option>";
}
$class_form .= "</select>";
$class_form .= "</form>";

$current_view = "new";
include(gettpl("adminpanel", $newobject));

$vars = $newobject->GetVars();
$newobject->loadClassIcon();

$left = img(geticon($newobject->getIcon()))."&nbsp;".ucf(i18n("new"));
$right = ucf(i18n("class")).": $class_form";
$center = "";
include(gettpl("big_title"));

?>
<form name="sEdit" id="sEdit" action="javascript:void(null);" onsubmit="Post('new', 'zone_main', 'sEdit');">
	<input class="hidden" type="hidden" name="action" value="save"/>
	<input class="hidden" type="hidden" name="path" value="<?=$object->getPathInTree()?>"/>
	<input class="hidden" type="hidden" name="class_name" value="<?=$newobject->getClassName()?>"/>
	<div class="main">
		<table class="top_edit_table">
			<tr>
				<td>
					<?=ucf(i18n("name"))?>: <input class="form" type="text" name="name" value="<?=$newobject->getName()?>">
				</td>
				<td>
					<?=ucf(i18n("icon"))?>: <input class="form" type="text" name="icon" value="">
				</td>
				<td>
					<?=ucf(i18n("language"))?>:
					<select class="select" name="language">
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
				</td>
			</tr>
		</table>
	</div>

	<div class="main">
		<?
		if (isset($newobject->vars))
		{
			foreach ($newobject->vars as $var)
				echo $var->getName().": (".$var->getType().")<br/><div class=\"container\">".$var->getEdit("objectmanagerForm")."</div><br/>";
		}
		?>
		<input class="submit" id="submitButton" type="submit" value="<?=ucf(i18n("save"))?>"/>
	</div>
</form>

