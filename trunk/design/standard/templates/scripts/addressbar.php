<?

function DrawAddressbar($path)
{
	echo Img(geticon("location"));
	?>
	<form id="sAddressbarForm" action="javascript:void(null);" onsubmit="sAddressbarCall();">
		<input class="location" type="text" name="path" value="<?=$path?>">
		<input id="sAddressbarSubmit" class="submit" type="submit" value="Go">
	</form>
	<?
}

?>