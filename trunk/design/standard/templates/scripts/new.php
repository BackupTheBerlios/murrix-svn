<?
$class_form = "<form name=\"sClassSelect\" id=\"sClassSelect\" action=\"javascript:void(null);\" onsubmit=\"javascript:void(null)\">";
$class_form .= "<select class=\"select\" onchange=\"Post('new','sClassSelect');\" name=\"class_name\">";
$classlist = getClassList();
foreach ($classlist as $class_name)
{
	$selected = "";
	if ($class_name == $newobject->getClassName())
		$selected = "selected";

	//if ($object->hasRight("create_subnodes", array($class_name)))
		$class_form .= "<option $selected value=\"$class_name\">".ucf(str_replace("_", " ", $class_name))."</option>";
}
$class_form .= "</select>";
$class_form .= "</form>";

$current_view = "new";
include(gettpl("adminpanel", $newobject));

$vars = $newobject->GetVars();
$newobject->loadClassIcon();

$left = img(geticon($newobject->getIcon()))."&nbsp;".ucf(i18n("new"));
$right = $class_form;
$center = "";
include(gettpl("big_title"));

?>
<form name="sEdit" id="sEdit" action="javascript:void(null);" onsubmit="Post('new','sEdit');">
	<input class="hidden" type="hidden" name="action" value="save"/>
	<input class="hidden" type="hidden" name="node_id" value="<?=$object->getNodeId()?>"/>
	<input class="hidden" type="hidden" name="class_name" value="<?=$newobject->getClassName()?>"/>
	<?
	if (count($_SESSION['murrix']['languages']) > 1)
	{
	?>
		<div class="adminpanel">
		<?
			foreach ($_SESSION['murrix']['languages'] as $language)
			{
				$result = array_diff($_SESSION['murrix']['languages'], array($language));
	
				$hide = "";
				foreach ($result as $lang)
					$hide .= "document.getElementById('edit_$lang').style.display='none';document.getElementById('select_$lang').className='tab';";
	
				$show = "document.getElementById('edit_$language').style.display='block';document.getElementById('select_$language').className='tab_selected';";
					
				?><a id="select_<?=$language?>" class="tab<?=($language == $_SESSION['murrix']['language'] ? "_selected" : "")?>" href="javascript:void(null)" onclick="<?=$hide.$show?>"><?=img(imgpath("$language.jpg"))." ".ucf(i18n($language))?></a><?
			}
		?>
		</div>
		<br/>
		<div class="clear"></div>
		<?
	}
	
	foreach ($_SESSION['murrix']['languages'] as $language)
	{
		$style = "";
		if ($language != $_SESSION['murrix']['language'])
			$style = "style=\"display: none;\"";
	
	?>
		<div id="edit_<?=$language?>" <?=$style?>>
			<div class="main">
				<div class="container">
					<table class="top_edit_table">
						<tr>
							<td class="left">
								<?=ucf(i18n("name"))?>: <input class="input" type="text" name="<?=$language?>_name"/>
							</td>
							<td class="right">
								<?=ucf(i18n("icon"))?>:
								<img id="<?=$language?>_icon_img" src="<?=geticon($newobject->getIcon())?>"/>
								<input class="hidden" type="hidden" name="<?=$language?>_icon" id="icon"/>
								<a href="javascript:void(null);" onclick="popWin=open('<?=gettpl_www("popups/iconbrowse")?>?input_id=<?=$language?>_icon&form_id=sEdit','PopUpWindow','width=500,height=400,scrollbars=1,status=0'); popWin.opener = self; popWin.focus(); popWin.moveTo(150,50); return false"><?=ucf(i18n("browse"))?></a>
							</td>
						</tr>
					</table>
				</div>
			</div>
		
			<div class="main">
				<div class="container">
				<?
					if (isset($newobject->vars))
					{
						foreach ($newobject->vars as $var)
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
									echo $var->getEdit("sEdit", $language."_");
									
									$javascript .= $var->getJavascript("sEdit", $language."_");
									?>
								</fieldset>
							</div>
							<br/>
						<?
						}
					}
					
					$submit = ucf(i18n("save"));
					if (count($_SESSION['murrix']['languages']) > 1)
						$submit = ucf(i18n("save $language version"));
						
					?>
					<input class="submit" id="submitButton" type="submit" onclick="document.getElementById('language').value='<?=$language?>'" value="<?=$submit?>"/>
				</div>
			</div>
		</div>
	<?
	}
	
	if (count($_SESSION['murrix']['languages']) > 1)
	{
	?>
		<div class="main">
			<div class="container">
				<input class="hidden" type="hidden" id="language" name="language" value=""/>
				<input class="submit" id="submitButton" type="submit" value="<?=ucf(i18n("save all languages"))?>"/>
			</div>
		</div>
	<?
	}
	?>
</form>