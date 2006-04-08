<?
$current_view = "show";
include(gettpl("scripts/class/adminpanel"));

$left = img(geticon("settings"))."&nbsp;".ucf(i18n("class administration"));
$right = $center = "";
include(gettpl("big_title"));

$object = new mObject();
$object->setClassName($args['name']);
$object->loadVars();
$object->loadClassIcon();

?>
<form name="sEdit" id="sEdit" action="javascript:void(null);" onsubmit="Post('class','zone_main','sEdit');">
	<input class="hidden" type="hidden" name="action" value="save"/>
		
	<div class="main">
		<table class="top_edit_table">
			<tr>
				<td>
					<?=ucf(i18n("name"))?>: <input class="form" type="text" name="name" value="<?=$args['name']?>"/>
				</td>
				<td>
					<?=ucf(i18n("icon"))?>: <input class="form" type="text" name="icon" value="<?=$object->class_icon?>" id="icon"/>
					<a href="javascript:void(null);" onclick="popWin=open('icon_browse.php?input_id=icon&form_id=sEdit','PopUpWindow','width=500,height=400,scrollbars=1,status=0'); popWin.opener = self; popWin.focus(); popWin.moveTo(150,50); return false"><?=ucf(i18n("browse"))?></a>
				</td>
			</tr>
		</table>
	</div>
	<?
	
	foreach ($object->vars as $var)
	{
	?>
		<div class="main">
			<div style="padding: 5px; background-color: #B6BAFF; font-weight: bold; font-size: 14px;">
				<div style="float: right;"><?=img(geticon("delete"))?></div>
				<?=$var->getName()?>
			</div>
			<div class="clear"></div>
			<table width="100%" style="padding: 5px; background-color: #ECEDFF; font-weight: normal;">
				<tr>
					<td style="width: 150px;">
						<div style="font-weight: bold;">
							<?=ucf(i18n("name"))?>
						</div>
						<input class="form" type="text" value="<?=$var->getName(true)?>"/>
						
						<br/><br/>
						
						<div style="font-weight: bold;">
							<?=ucf(i18n("type"))?>
						</div>
						<select class="form">
						<?
							global $datatypes;
							foreach ($datatypes as $datatype)
							{
							?>
								<option <?=($datatype == $var->getType() ? "selected" : "")?> value="<?=$datatype?>"><?=$datatype?></option>
							<?
							}
						?>
						</select>
						
						<br/><br/>
						
						<div style="font-weight: bold;">
							<?=ucf(i18n("required"))?>
						</div>
						<select class="form">
							<option <?=(!$var->getRequired() ? "selected" : "")?> value="0">No</option>
							<option <?=($var->getRequired() ? "selected" : "")?> value="1">Yes</option>
						</select>
					</td>
					<td>
						<div style="font-weight: bold;">
							<?=ucf(i18n("extra"))?>
						</div>
						<textarea cols="50" rows="6" class="form"><?=$var->getExtra()?></textarea>
					</td>
					<td>
						<div style="font-weight: bold;">
							<?=ucf(i18n("comment"))?>
						</div>
						<textarea cols="50" rows="6" class="form"><?=$var->getComment()?></textarea>
					</td>
					<td style="text-align: center; vertical-align: middle;">
						<?=img(imgpath("up.png"))?><br/>
						<?=img(imgpath("down.png"))?>
					</td>
				</tr>
			</table>
		</div>
	<?
	}
	?>
	
	<div class="main">
		<input class="submit" id="submitButton" type="submit" value="<?=ucf(i18n("save"))?>"/>
	</div>
</form>