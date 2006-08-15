<?
$parent = new mObject($args['parent_node_id']);

if ($parent->hasRight("create"))
{
	$class_form = "<form name=\"sClassSelect\" id=\"sClassSelect\" action=\"javascript:void(null);\" onsubmit=\"javascript:void(null)\">";
	$class_form .= "<select class=\"select\" onchange=\"Post('new','sClassSelect');\" name=\"class_name\">";
	$classlist = getClassList();
	foreach ($classlist as $class_name)
	{
		$selected = $class_name == $object->getClassName() ? "selected" : "";
		$class_form .= "<option $selected value=\"$class_name\">".ucf(str_replace("_", " ", $class_name))."</option>";
	}
	$class_form .= "</select>";
	$class_form .= "</form>";
}

echo compiletpl("scripts/show/tabs", array("view"=>"new"), $parent);
echo compiletpl("title/big", array("left"=>img(geticon($object->getIcon()))."&nbsp;".ucf(i18n("new")),"right"=>$class_form), $object);
?>
<form name="sEdit" id="sEdit" action="javascript:void(null);" onsubmit="Post('new','sEdit');">
	<input class="hidden" type="hidden" name="action" value="save"/>
	<input class="hidden" type="hidden" name="node_id" value="<?=$args['parent_node_id']?>"/>
	<input class="hidden" type="hidden" name="class_name" value="<?=$object->getClassName()?>"/>
	<input class="hidden" type="hidden" id="language" name="language" value="eng"/>
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
								<input class="hidden" type="hidden" name="<?=$language?>_icon" id="icon"/>
								<a href="javascript:void(null);" onclick="popWin=open('<?=gettpl_www("popups/iconbrowse")?>?input_id=<?=$language?>_icon&form_id=sEdit','PopUpWindow','width=500,height=400,scrollbars=1,status=0'); popWin.opener = self; popWin.focus(); popWin.moveTo(150,50); return false"><img id="<?=$language?>_icon_img" src="<?=geticon($object->getIcon())?>"/></a>
							</td>
						</tr>
					</table>
				</div>
			</div>
		
			<div class="main">
				<div class="container">
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
						$submit = ucf(i18n("save"))." ".i18n($language)." ".i18n("version");
						
					?>
					<input class="submit" id="submitButton" type="button" onclick="document.getElementById('language').value='<?=$language?>';Post('new','sEdit');" value="<?=$submit?>"/>
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
				
				<input class="submit" id="submitButton" type="submit" value="<?=ucf(i18n("save all languages"))?>"/>
			</div>
		</div>
	<?
	}
?>
</form>