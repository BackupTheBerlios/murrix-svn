<?
if (isset($_SESSION['murrix']['languages']) && is_array($_SESSION['murrix']['languages']))
{
	foreach ($_SESSION['murrix']['languages'] as $language)
	{
		if ($language == $_SESSION['murrix']['language'])
			continue;
		?>
		<form name="sLangswitch" id="sLangswitch" action="javascript:void(null);" onsubmit="Post('langswitch','sLangswitch');">
			<input class="hidden" type="hidden" name="language" value="<?=$language?>"/>
			<input type="image" src="<?=imgpath("$language.jpg")?>" alt="<?=ucf(i18n($language))?>"/>
		</form>
		<?
	}
}
?>