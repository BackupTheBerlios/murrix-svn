<select class="form" id="<?=$args['varname']?>" name="<?=$args['varname']?>">
<?
foreach ($args['list'] as $key => $value)
	echo "<option value=\"$value\" ".($value == $args['value'] ? "selected" : "").">$key</option>";
?>
</select>