<?
$current_view = "settings";
include(gettpl("adminpanel", $object));

$left = img(geticon("settings"))."&nbsp;".ucf(i18n("settings"));
$right = $center = "";
include(gettpl("big_title"));

?>
<form name="sSettings" id="sSettings" action="javascript:void(null);" onsubmit="Post('settings','sSettings');">
	<input class="hidden" type="hidden" name="action" value="meta"/>
	<input class="hidden" type="hidden" name="node_id" value="<?=$object->getNodeId()?>"/>
	
	<div class="main">
		<fieldset>
			<legend>
				<?=ucf(i18n("display"))?>
			</legend>
			
			<?=ucf(i18n("items per page"))?>
			<select class="form" name="children_show_num_per_page">
			<?
				$list = array(5 => 5, 10 => 10, 25 => "", 50 => 50, 100 => 100, "all" => "all");
				foreach ($list as $key => $item)
				{
					$selected = "";
					if ($key == $object->getMeta("children_show_num_per_page", 25))
						$selected = "selected";
	
					echo "<option $selected value=\"$item\">".(is_int($key) ? $key : ucf(i18n($key)))."</option>";
				}
			?>
			</select>
			<br/><br/>
			<?=ucf(i18n("view"))?>
			<select class="form" name="view">
			<?
				$viewlist = array("list" => "", "thumbnailes" => "thumbnailes", "table" => "table");
				foreach ($viewlist as $key => $view)
				{
					$selected = "";
					if ($key == $object->getMeta("view", ""))
						$selected = "selected";
				
					echo "<option $selected value=\"$view\">".ucf(i18n($key))."</option>";
				}
			?>
			</select>
		</fieldset>
		<br/>
		<fieldset>
			<legend>
				<?=ucf(i18n("sorting"))?>
			</legend>
		
			<?=ucf(i18n("sort by"))?>
			<select class="form" name="sort_by">
			<?
				$value = $object->getMeta("sort_by", "property:name");
				$list = array("custom" => "custom", "name" => "property:name", "class" => "property:class", "language" => "property:language", "icon" => "property:icon", "version" => "property:version", "created" => "property:created", "creator" => "property:creator");
				foreach ($list as $key => $item)
				{
					$selected = "";
					
					if ($item == $value)
						$selected = "selected";
						
					if ($item == "property:name")
						$item = "";
	
					echo "<option $selected value=\"$item\">".ucf(i18n($key))."</option>";
				}
				
				$custom = "";
				if (!in_array($value, $list))
					$custom = $value;
			?>
			</select> <input class="form" type="text" name="sort_by_custom" value="<?=$custom?>"/>
			<br/><br/>
			<?=ucf(i18n("sort direction"))?>
			<select class="form" name="sort_direction">
				<option <?=(($object->getMeta("sort_direction", "") == "") ? "selected" : "")?> value=""><?=ucf(i18n("descending"))?></option>
				<option <?=($object->getMeta("sort_direction", "") == "asc" ? "selected" : "")?> value="asc"><?=ucf(i18n("ascending"))?></option>
			</select>
		</fieldset>
		<br/>
		<fieldset>
			<legend>
				<?=ucf(i18n("comments"))?>
			</legend>
			
			<?=ucf(i18n("comments per page"))?>
			<select class="form" name="comment_show_num_per_page">
			<?
				$list = array(5 => 5, 10 => 10, 25 => "", 50 => 50, 100 => 100, "all" => "all");
				foreach ($list as $key => $item)
				{
					$selected = "";
					if ($key == $object->getMeta("comment_show_num_per_page", 25))
						$selected = "selected";
	
					echo "<option $selected value=\"$item\">".(is_int($key) ? $key : ucf(i18n($key)))."</option>";
				}
			?>
			</select>
			<br/><br/>
			<?=ucf(i18n("show comments"))?>
			<input class="form" type="checkbox" name="show_comments" <?=($object->getMeta("show_comments", 0) ? "checked" : "")?>/>
		</fieldset>
		<br/>
		<fieldset>
			<legend>
				<?=ucf(i18n("tabs"))?>
			</legend>
			
			<?=ucf(i18n("show versionstab"))?>
			<input class="form" type="checkbox" name="show_versionstab" <?=($object->getMeta("show_versionstab", 0) ? "checked" : "")?>/>
			<br/><br/>
			<?=ucf(i18n("show linkstab"))?>
			<input class="form" type="checkbox" name="show_linkstab" <?=($object->getMeta("show_linkstab", 0) ? "checked" : "")?>/>
			
		</fieldset>
		<br/>
		<fieldset>
			<legend>
				<?=ucf(i18n("creating"))?>
			</legend>
		
			<?=ucf(i18n("default new class"))?>
			<select class="form" name="default_class_name">
			<?
				$classlist = getClassList();
				foreach ($classlist as $class_name)
				{
					$value = $object->getMeta("default_class_name", "");
					$selected = "";
					
					if (($class_name == $value) || ($class_name == "folder" && empty($value)))
						$selected = "selected";
						
					$class_name2 = $class_name;
					if ($class_name == "folder")
						$class_name2 = "";
				
					echo "<option $selected value=\"$class_name2\">".ucf(str_replace("_", " ", $class_name))."</option>";
				}
			?>
			</select>
			<br/><br/>
			<?=ucf(i18n("initial group"))?>
			<select class="form" name="initial_group">
			<?
				$group = new mGroup();
				$groups = $group->getList();
				echo "<option $selected value=\"\">".ucf(i18n("not set"))."</option>";
				foreach ($groups as $group)
				{
					$selected = "";
					if ($group->id == $object->getMeta("initial_group", ""))
						$selected = "selected";
	
					echo "<option $selected value=\"".$group->id."\">".$group->name."</option>";
				}
			?>
			</select>
			<br/><br/>
			<?=ucf(i18n("initial rights"))?>
			<input class="form" type="text" name="initial_rights" value="<?=$object->getMeta("initial_rights", "")?>"/>
		</fieldset>
		
		<br/>
		<input class="submit" id="submitButton" type="submit" value="<?=ucf(i18n("save"))?>"/>
	</div>
</form>