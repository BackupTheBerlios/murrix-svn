<?=compiletpl("title/big", array("left"=>"Configuration"))?>

<form name="sInstall" id="sInstall" action="javascript:void(null);" onsubmit="Post('install','sInstall')">
	<input class="hidden" type="hidden" name="action" value="finish">
	Default theme<br/>
	<select class="selectbox" name="theme">
	<?
		global $abspath;
		$folders = GetSubfolders("$abspath/design");
		foreach ($folders as $folder)
			echo "<option ".($folder == $args['theme'] ? "selected" : "")." value=\"$folder\">".ucf($folder)."</option>";
	?>
	</select><br/>
	Imagesize<br/>
	<input class="textline" name="imgsize" value="<?=$args['imgsize']?>" type="text"><br/>
	Thumbnailsize<br/>
	<input class="textline" name="thumbsize" value="<?=$args['thumbsize']?>" type="text"><br/>
	Instant thumbnail creation<br/>
	<select class="selectbox" name="instantthumbs">
		<option <?=("true" == $args['instantthumbs'] ? "selected" : "")?> value="true">Yes</option>
		<option <?=("false" == $args['instantthumbs'] ? "selected" : "")?> value="false">No</option>
	</select>
	<br/><br/>
	
	Default language<br/>
	<input class="textline" name="default_lang" value="<?=$args['default_lang']?>" type="text"><br/>
	<br/><br/>
	
	Default path<br/>
	<input class="textline" name="default_path" value="<?=$args['default_path']?>" type="text"><br/>
	<br/><br/>
	
	Transport<br/>
	<select class="selectbox" name="transport">
		<option <?=("standard" == $args['transport'] ? "selected" : "")?> value="standard">Standard</option>
		<option <?=("ajax" == $args['transport'] ? "selected" : "")?> value="ajax">Ajax</option>
	</select>
	<br/><br/>
	
	Administrator password<br/>
	<input class="textline" name="password" value="<?=$args['password']?>" type="password"><br/>
</form>

<center>
<?
	echo cmd(img(imgpath("left.png")), "exec=install&action=database");
	echo runjs(img(imgpath("right.png")), "Post('install','sInstall')");
?>
</center>