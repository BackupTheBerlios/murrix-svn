<?

class mForm
{
	var $name;
	
	function mForm($name)
	{
		$this->name = $name;
	}
	
	function begin($target)
	{?>
		<form name="<?=$this->name?>" id="<?=$this->name?>" action="javascript:void(null);" onsubmit="Post('<?=$target?>','<?=$this->name?>')">
	<?}
	
	function end()
	{?>
		</form>
	<?}
	
	function input($name, $args)
	{
		$string = "";
		foreach ($args as $key => $value)
			$string .= " $key=\"$value\"";
		?>
		<input name="<?=$name?>" id="<?=$name?>"<?=$string?>/>
	<?}
	
	function select($name, $args, $list)
	{
		$string = "";
		foreach ($args as $key => $value)
			$string .= " $key=\"$value\"";
		?>
		<select name="<?=$name?>" id="<?=$name?>"<?=$string?>/>
		
		</select>
	<?}
<select class="selectbox" name="instantthumbs">
		<option <?=("true" == $args['instantthumbs'] ? "selected" : "")?> value="true">Yes</option>
		<option <?=("false" == $args['instantthumbs'] ? "selected" : "")?> value="false">No</option>
	</select>
}

?>